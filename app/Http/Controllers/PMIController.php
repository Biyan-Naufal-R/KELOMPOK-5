<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\Distribution;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PMIController extends Controller
{
    public function dashboard()
    {
        $bloodStockSummary = BloodStock::where('status', 'available')
            ->selectRaw('blood_type, rhesus, SUM(quantity) as total')
            ->groupBy('blood_type', 'rhesus')
            ->get();

        $todayRequests = BloodRequest::whereDate('created_at', today())->count();
        $pendingVerification = BloodRequest::where('status', 'pending')->count();
        $inProcess = BloodRequest::whereIn('status', ['approved', 'processed'])->count();
        
        $todayDistributions = Distribution::whereDate('created_at', today())
            ->with('bloodRequest.hospital')
            ->get();

        return view('pmi.dashboard', compact(
            'bloodStockSummary',
            'todayRequests',
            'pendingVerification',
            'inProcess',
            'todayDistributions'
        ));
    }

    public function bloodStock()
    {
        $bloodStocks = BloodStock::with('hospital')->get();
        return view('pmi.blood-stock', compact('bloodStocks'));
    }

    public function updateBloodStock(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:positive,negative',
            'quantity' => 'required|integer|min:1',
            'source' => 'required|in:donor,mobile_unit',
            'expiry_date' => 'required|date|after_or_equal:today'
        ]);

        // Create stock record
        BloodStock::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_blood_stock',
            'description' => 'Menambah stok darah ' . $validated['blood_type'] . $validated['rhesus']
        ]);

        return redirect()->back()->with('success', 'Stok darah berhasil ditambahkan.');
    }

    public function adjustStock(Request $request)
    {
        $validated = $request->validate([
            'stock_id' => 'required|exists:blood_stocks,id',
            'adjustment_type' => 'required|in:kadaluarsa,kerusakan,return,lainnya',
            'adjustment_quantity' => 'required|integer|min:1',
            'adjustment_note' => 'nullable|string|max:1000',
        ]);

        $stock = \App\Models\BloodStock::findOrFail($validated['stock_id']);

        $qty = (int) $validated['adjustment_quantity'];

        if ($validated['adjustment_type'] === 'return') {
            $stock->quantity = $stock->quantity + $qty;
        } else {
            $stock->quantity = max(0, $stock->quantity - $qty);
            if ($validated['adjustment_type'] === 'kadaluarsa') {
                $stock->status = 'expired';
            } elseif ($validated['adjustment_type'] === 'kerusakan') {
                $stock->status = 'damaged';
            }
        }

        $stock->save();

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'adjust_stock',
            'description' => 'Penyesuaian stok ID: ' . $stock->id . ' - ' . $validated['adjustment_type'] . ' (' . $qty . ') ' . ($validated['adjustment_note'] ?? '')
        ]);

        return redirect()->back()->with('success', 'Penyesuaian stok berhasil disimpan.');
    }

    public function deleteBloodStock($id)
    {
        $stock = \App\Models\BloodStock::find($id);
        if (! $stock) {
            return response()->json(['success' => false], 404);
        }

        $stock->delete();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_stock',
            'description' => 'Menghapus stok darah ID: ' . $id
        ]);

        return response()->json(['success' => true]);
    }

    public function bloodRequests()
    {
        $requests = BloodRequest::with('hospital', 'createdByUser')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pmi.blood-requests', compact('requests'));
    }

    public function verifyBloodRequest(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected'
        ]);

        $bloodRequest->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'approved_by' => auth()->id()
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'verify_blood_request',
            'description' => 'Memverifikasi permintaan darah ID: ' . $id
        ]);

        return redirect()->back()->with('success', 'Permintaan berhasil diverifikasi.');
    }

    public function distribution()
    {
        $distributions = Distribution::with(['bloodRequest.hospital', 'createdByUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $approvedRequests = BloodRequest::where('status', 'approved')
            ->with('hospital')
            ->get();
            
        return view('pmi.distribution', compact('distributions', 'approvedRequests'));
    }

    public function createDistribution(Request $request)
    {
        $validated = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'driver_name' => 'required|string|max:255',
            'vehicle_info' => 'required|string|max:255',
            'estimated_arrival' => 'required|date|after:now'
        ]);

        $distribution = Distribution::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => 'preparing'
        ]);

        // Update blood request status
        BloodRequest::where('id', $validated['blood_request_id'])
            ->update(['status' => 'processed']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_distribution',
            'description' => 'Membuat distribusi untuk permintaan ID: ' . $validated['blood_request_id']
        ]);

        return redirect()->back()->with('success', 'Distribusi berhasil dibuat.');
    }

    /**
     * Update distribution status (from PMI distribution page)
     */
    public function updateDistributionStatus(Request $request, $id)
    {
        $distribution = Distribution::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:preparing,on_delivery,delivered',
            'actual_arrival' => 'nullable|date',
            'update_notes' => 'nullable|string|max:1000',
        ]);

        $distribution->status = $validated['status'];

        if (!empty($validated['actual_arrival'])) {
            $distribution->actual_arrival = Carbon::parse($validated['actual_arrival']);
        }

        if (!empty($validated['update_notes'])) {
            $distribution->notes = $validated['update_notes'];
        }

        $distribution->save();

        if ($validated['status'] === 'delivered') {
            $distribution->bloodRequest()->update(['status' => 'delivered']);
        } elseif ($validated['status'] === 'on_delivery') {
            $distribution->bloodRequest()->update(['status' => 'processed']);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_distribution_status',
            'description' => 'Memperbarui status distribusi ID: ' . $distribution->id . ' menjadi ' . $distribution->status
        ]);

        return redirect()->back()->with('success', 'Status distribusi berhasil diperbarui.');
    }

    public function hospitals()
    {
        $hospitals = Hospital::with(['bloodRequests', 'users'])->get();

        $totalRequestsThisMonth = \App\Models\BloodRequest::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('pmi.hospitals', compact('hospitals', 'totalRequestsThisMonth'));
    }

   
    public function showHospital($id)
    {
        $hospital = Hospital::findOrFail($id);
        return response()->json($hospital);
    }

    /**
     * Store a newly created hospital (Data RS)
     */
    public function storeHospital(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive',
            'admin_name' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email'
        ]);

        $hospital = Hospital::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 'active'
        ]);

        // create admin user
        if (!empty($validated['admin_email']) && !empty($validated['admin_name'])) {
            $password = Str::random(10);
            User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($password),
                'user_type' => 'rumah_sakit',
                'hospital_id' => $hospital->id
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_hospital',
            'description' => 'Membuat rumah sakit: ' . $hospital->name
        ]);

        return redirect()->route('pmi.hospitals')->with('success', 'Rumah sakit berhasil ditambahkan.');
    }

    /**
     * Update hospital information
     */
    public function updateHospital(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $hospital->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status']
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_hospital',
            'description' => 'Memperbarui rumah sakit: ' . $hospital->name
        ]);

        return redirect()->route('pmi.hospitals')->with('success', 'Rumah sakit berhasil diperbarui.');
    }

   
    public function toggleHospitalStatus(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $hospital->status = $validated['status'];
        $hospital->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'toggle_hospital_status',
            'description' => 'Mengubah status rumah sakit: ' . $hospital->name
        ]);

        return response()->json(['success' => true]);
    }


    public function settings()
    {
        $users = User::where('user_type', 'pmi')->get();
        return view('pmi.settings', compact('users'));
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'pmi'
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_pmi_user',
            'description' => 'Membuat user PMI: ' . $user->email
        ]);

        return redirect()->route('pmi.settings')->with('success', 'Pengguna PMI berhasil dibuat.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email'],
            'password' => 'nullable|string|min:6'
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_pmi_user',
            'description' => 'Memperbarui user PMI: ' . $user->email
        ]);

        return redirect()->route('pmi.settings')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun yang sedang masuk.'], 403);
        }

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_pmi_user',
            'description' => 'Menghapus user PMI: ' . $user->email
        ]);

        return response()->json(['success' => true]);
    }
}