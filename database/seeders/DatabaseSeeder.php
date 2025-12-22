<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Hospitals
        $hospitals = [
            [
                'name' => 'RSUD Dr. Soetomo',
                'address' => 'Jl. Mayjen Prof. Dr. Moestopo No.6-8, Surabaya',
                'phone' => '031-5501076',
                'email' => 'info@rsudsoetomo.go.id',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'RSUP Dr. Kariadi',
                'address' => 'Jl. Dr. Sutomo No.16, Semarang',
                'phone' => '024-8413476',
                'email' => 'info@rskariadi.co.id',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'RSUP Dr. Sardjito',
                'address' => 'Jl. Kesehatan No.1, Yogyakarta',
                'phone' => '0274-587333',
                'email' => 'info@sardjitohospital.co.id',
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('hospitals')->insert($hospitals);

        // Seed PMI Users
        $pmiUsers = [
            [
                'name' => 'Admin PMI Pusat',
                'email' => 'admin@pmi.go.id',
                'password' => Hash::make('password123'),
                'user_type' => 'pmi',
                'hospital_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Staff Logistik PMI',
                'email' => 'logistik@pmi.go.id',
                'password' => Hash::make('password123'),
                'user_type' => 'pmi',
                'hospital_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Koordinator Distribusi',
                'email' => 'distribusi@pmi.go.id',
                'password' => Hash::make('password123'),
                'user_type' => 'pmi',
                'hospital_id' => null,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('users')->insert($pmiUsers);

        // Seed Hospital Users
        $hospitalUsers = [
            [
                'name' => 'Dr. Ahmad Wijaya',
                'email' => 'ahmad@soetomo.go.id',
                'password' => Hash::make('password123'),
                'user_type' => 'rumah_sakit',
                'hospital_id' => 1,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Siti Rahayu, S.Kep',
                'email' => 'siti@kariadi.co.id',
                'password' => Hash::make('password123'),
                'user_type' => 'rumah_sakit',
                'hospital_id' => 2,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Budi Santoso, Amd.Kep',
                'email' => 'budi@sardjito.co.id',
                'password' => Hash::make('password123'),
                'user_type' => 'rumah_sakit',
                'hospital_id' => 3,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('users')->insert($hospitalUsers);

        // Seed Blood Stocks
        $bloodStocks = [];
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['positive', 'negative'];
        $sources = ['donor', 'mobile_unit'];

        foreach ($bloodTypes as $type) {
            foreach ($rhesusTypes as $rhesus) {
                $bloodStocks[] = [
                    'blood_type' => $type,
                    'rhesus' => $rhesus,
                    'quantity' => rand(20, 100),
                    'expiry_date' => Carbon::now()->addDays(rand(10, 40)),
                    'status' => 'available',
                    'source' => $sources[array_rand($sources)],
                    'hospital_id' => null, // Stok PMI
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        DB::table('blood_stocks')->insert($bloodStocks);

        // Seed Blood Requests
        $bloodRequests = [
            [
                'hospital_id' => 1,
                'blood_type' => 'A',
                'rhesus' => 'positive',
                'quantity' => 5,
                'urgency' => 'urgent',
                'patient_info' => 'Pasien operasi jantung, usia 45 tahun',
                'status' => 'approved',
                'created_by' => 4,
                'approved_by' => 1,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'hospital_id' => 2,
                'blood_type' => 'B',
                'rhesus' => 'negative',
                'quantity' => 3,
                'urgency' => 'emergency',
                'patient_info' => 'Kecelakaan lalu lintas, multiple trauma',
                'status' => 'processed',
                'created_by' => 5,
                'approved_by' => 2,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(6),
            ],
            [
                'hospital_id' => 3,
                'blood_type' => 'O',
                'rhesus' => 'positive',
                'quantity' => 10,
                'urgency' => 'normal',
                'patient_info' => 'Stok rutin rumah sakit',
                'status' => 'pending',
                'created_by' => 6,
                'approved_by' => null,
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(3),
            ]
        ];

        DB::table('blood_requests')->insert($bloodRequests);

        // Seed Distributions
        $distributions = [
            [
                'blood_request_id' => 1,
                'driver_name' => 'Joko Susilo',
                'vehicle_info' => 'Toyota Hilux B 1234 CD',
                'departure_time' => Carbon::now()->subDays(1)->addHours(2),
                'estimated_arrival' => Carbon::now()->subDays(1)->addHours(4),
                'actual_arrival' => Carbon::now()->subDays(1)->addHours(3),
                'status' => 'delivered',
                'receipt_proof' => 'receipt_1.jpg',
                'notes' => 'Pengiriman tepat waktu, kondisi darah baik',
                'created_by' => 3,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'blood_request_id' => 2,
                'driver_name' => 'Bambang Pratama',
                'vehicle_info' => 'Mitsubishi L300 B 5678 EF',
                'departure_time' => Carbon::now()->subHours(5),
                'estimated_arrival' => Carbon::now()->addHours(1),
                'actual_arrival' => null,
                'status' => 'on_delivery',
                'receipt_proof' => null,
                'notes' => 'Pengiriman emergency, prioritas tinggi',
                'created_by' => 3,
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5),
            ]
        ];

        DB::table('distributions')->insert($distributions);

        // Seed Activity Logs
        $activityLogs = [
            [
                'user_id' => 1,
                'action' => 'login',
                'description' => 'Admin PMI melakukan login',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => 4,
                'action' => 'create_request',
                'description' => 'Membuat permintaan darah untuk pasien operasi jantung',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => 2,
                'action' => 'approve_request',
                'description' => 'Menyetujui permintaan darah dari RSUD Dr. Soetomo',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        DB::table('activity_logs')->insert($activityLogs);

        $this->command->info('Database seeded successfully!');
        $this->command->info('PMI Login: admin@pmi.go.id / password123');
        $this->command->info('RS Login: ahmad@soetomo.go.id / password123');
        $this->command->info('Other RS Logins:');
        $this->command->info('  - siti@kariadi.co.id / password123');
        $this->command->info('  - budi@sardjito.co.id / password123');
    }
}