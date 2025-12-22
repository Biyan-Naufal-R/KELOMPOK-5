<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Distribution;
use App\Models\Hospital;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RSController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $hospital = Hospital::find($user->hospital_id);

        $recentRequests = BloodRequest::where('hospital_id', $hospital->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingRequests = BloodRequest::where('hospital_id', $hospital->id)
            ->where('status', 'pending')
            ->count();

        $weeklyReceipts = Distribution::whereHas('bloodRequest', function($query) use ($hospital) {
            $query->where('hospital_id', $hospital->id);
        })->where('status', 'delivered')
          ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
          ->get();

        return view('rs.dashboard', compact(
            'recentRequests',
            'pendingRequests',
            'weeklyReceipts'
        ));
    }

    public function createRequest()
    {
        return view('rs.create-request');
    }

    public function storeRequest(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'blood_type' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:positive,negative',
            'quantity' => 'required|integer|min:1',
            'urgency' => 'required|in:normal,urgent,emergency',
            'patient_info' => 'nullable|string|max:500'
        ]);

        $bloodRequest = BloodRequest::create([
            ...$validated,
            'hospital_id' => $user->hospital_id,
            'created_by' => $user->id,
            'status' => 'pending'
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_blood_request',
            'description' => 'Membuat permintaan darah baru'
        ]);

        return redirect()->route('rs.requests')->with('success', 'Permintaan darah berhasil dibuat.');
    }

    public function requests()
    {
        $user = Auth::user();
        $requests = BloodRequest::where('hospital_id', $user->hospital_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rs.requests', compact('requests'));
    }

    public function bloodReceipt()
    {
        $user = Auth::user();
        $distributions = Distribution::whereHas('bloodRequest', function($query) use ($user) {
            $query->where('hospital_id', $user->hospital_id);
        })->with('bloodRequest')
          ->orderBy('created_at', 'desc')
          ->get();

        return view('rs.blood-receipt', compact('distributions'));
    }

    public function confirmReceipt(Request $request, $id)
    {
        $distribution = Distribution::findOrFail($id);
        
        $validated = $request->validate([
            'receipt_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        if ($request->hasFile('receipt_proof')) {
            $path = $request->file('receipt_proof')->store('receipts', 'public');
            $validated['receipt_proof'] = $path;
        }

        $distribution->update([
            'receipt_proof' => $validated['receipt_proof'],
            'status' => 'delivered',
            'actual_arrival' => now()
        ]);

        // Update blood request status
        $distribution->bloodRequest->update(['status' => 'completed']);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'confirm_receipt',
            'description' => 'Mengkonfirmasi penerimaan distribusi ID: ' . $id
        ]);

        return redirect()->back()->with('success', 'Penerimaan berhasil dikonfirmasi.');
    }

    public function history()
    {
        $user = Auth::user();
        $requests = BloodRequest::where('hospital_id', $user->hospital_id)
            ->with('distributions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('rs.history', compact('requests'));
    }

    public function profile()
    {
        $user = Auth::user();
        $hospital = Hospital::find($user->hospital_id);
        $staff = User::where('hospital_id', $hospital->id)->get();

        return view('rs.profile', compact('hospital', 'staff'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $hospital = Hospital::find($user->hospital_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email'
        ]);

        $hospital->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function addStaff(Request $request)
    {
        $user = Auth::user();
        $hospital = Hospital::find($user->hospital_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'user_type' => 'rumah_sakit',
            'hospital_id' => $hospital->id
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_rs_staff',
            'description' => 'Menambahkan staff RS: ' . $newUser->email
        ]);

        return redirect()->route('rs.profile')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function deleteStaff($id)
    {
        $user = Auth::user();
        $staff = User::findOrFail($id);

        // only allow deleting staff from the same hospital and not self
        if ($staff->hospital_id !== $user->hospital_id) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus pengguna dari rumah sakit lain.'], 403);
        }

        if ($staff->id === $user->id) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun yang sedang masuk.'], 403);
        }

        $staff->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'delete_rs_staff',
            'description' => 'Menghapus staff RS: ' . $staff->email
        ]);

        return response()->json(['success' => true]);
    }
}