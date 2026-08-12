<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments
        $departments = [
            ['name' => 'Management', 'code' => 'MGT'],
            ['name' => 'Information Technology', 'code' => 'IT'],
            ['name' => 'Finance & Accounting', 'code' => 'FIN'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Marketing', 'code' => 'MKT'],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Users (Demo Accounts + Others)
        $password = Hash::make('password');
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@procureflow.test', 'role' => 'Admin', 'department_id' => 2],
            ['name' => 'Requester User', 'email' => 'requester@procureflow.test', 'role' => 'Requester', 'department_id' => 5],
            ['name' => 'Procurement Officer', 'email' => 'procurement@procureflow.test', 'role' => 'Procurement', 'department_id' => 5],
            ['name' => 'Manager Approver', 'email' => 'manager@procureflow.test', 'role' => 'Manager', 'department_id' => 1],
            ['name' => 'Warehouse Staff', 'email' => 'warehouse@procureflow.test', 'role' => 'Warehouse', 'department_id' => 5],
            ['name' => 'Finance Officer', 'email' => 'finance@procureflow.test', 'role' => 'Finance', 'department_id' => 3],
        ];

        // Add 9 more random employees
        $roles = ['Employee', 'Requester'];
        for ($i = 1; $i <= 9; $i++) {
            $users[] = [
                'name' => "Employee $i",
                'email' => "employee$i@procureflow.test",
                'role' => $roles[array_rand($roles)],
                'department_id' => rand(1, 6),
            ];
        }

        foreach ($users as $idx => $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $password,
                'role' => $user['role'],
                'department_id' => $user['department_id'],
                'active' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&color=7F9CF5&background=EBF4FF',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign managers to departments
        DB::table('departments')->where('id', 1)->update(['manager_id' => 4]); // Manager Approver

        // 3. Vendors
        $vendors = [
            ['code' => 'VND-001', 'name' => 'PT Dell Technologies Indonesia', 'contact' => 'Budi Santoso', 'logo' => 'https://ui-avatars.com/api/?name=PT+Dell+Technologies+Indonesia&background=2563EB&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-002', 'name' => 'CV Office Plus', 'contact' => 'Sari Indah', 'logo' => 'https://ui-avatars.com/api/?name=CV+Office+Plus&background=059669&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-003', 'name' => 'PT Maju Logistik', 'contact' => 'Andi Pratama', 'logo' => 'https://ui-avatars.com/api/?name=PT+Maju+Logistik&background=9333EA&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1586528116311-ad8ed7c50800?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-004', 'name' => 'Cisco Systems Indonesia', 'contact' => 'Diana Wijaya', 'logo' => 'https://ui-avatars.com/api/?name=Cisco+Systems+Indonesia&background=EA580C&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-005', 'name' => 'PT Furniture Makmur', 'contact' => 'Rina Gunawan', 'logo' => 'https://ui-avatars.com/api/?name=PT+Furniture+Makmur&background=BE123C&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-006', 'name' => 'Bhinneka Mentari Dimensi', 'contact' => 'Toni Setiawan', 'logo' => 'https://ui-avatars.com/api/?name=Bhinneka+Mentari+Dimensi&background=0F766E&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1531973576160-7125cd663d86?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-007', 'name' => 'PT Global Stationers', 'contact' => 'Maya Sari', 'logo' => 'https://ui-avatars.com/api/?name=PT+Global+Stationers&background=0EA5E9&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1512314889357-e157c22f938d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-008', 'name' => 'Lenovo Indonesia', 'contact' => 'Eko Saputra', 'logo' => 'https://ui-avatars.com/api/?name=Lenovo+Indonesia&background=7C3AED&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-009', 'name' => 'PT Kawan Lama', 'contact' => 'Yudi Hermanto', 'logo' => 'https://ui-avatars.com/api/?name=PT+Kawan+Lama&background=8B5CF6&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1580983546513-48b48873426e?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['code' => 'VND-010', 'name' => 'Datascrip', 'contact' => 'Rina Melati', 'logo' => 'https://ui-avatars.com/api/?name=Datascrip&background=047857&color=ffffff', 'img' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
        ];

        foreach ($vendors as $v) {
            DB::table('vendors')->insert([
                'code' => $v['code'],
                'name' => $v['name'],
                'contact_person' => $v['contact'],
                'email' => strtolower(str_replace(' ', '', $v['contact'])) . '@' . strtolower(str_replace([' ', 'PT', 'CV'], '', $v['name'])) . '.com',
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => 'Jl. Sudirman No. ' . rand(1, 100) . ', Jakarta',
                'tax_number' => rand(10, 99) . '.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-' . rand(100, 999) . '.000',
                'logo' => $v['logo'],
                'image' => $v['img'],
                'rating' => rand(35, 50) / 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Categories & Products
        $categories = ['IT Equipment', 'Office Supplies', 'Furniture', 'Services'];
        foreach ($categories as $cat) {
            DB::table('categories')->insert(['name' => $cat, 'created_at' => now(), 'updated_at' => now()]);
        }

        $products = [
            ['name' => 'Dell XPS 15 Laptop', 'cat' => 1, 'price' => 25000000, 'img' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Lenovo ThinkPad T14', 'cat' => 1, 'price' => 18000000, 'img' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'MacBook Pro 14"', 'cat' => 1, 'price' => 32000000, 'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Cisco Meraki MR46', 'cat' => 1, 'price' => 15000000, 'img' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Logitech MX Master 3', 'cat' => 1, 'price' => 1500000, 'img' => 'https://images.unsplash.com/photo-1527814050087-379381547961?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Dell 27" 4K Monitor', 'cat' => 1, 'price' => 6000000, 'img' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Ergonomic Office Chair', 'cat' => 3, 'price' => 3500000, 'img' => 'https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Standing Desk', 'cat' => 3, 'price' => 5000000, 'img' => 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Meeting Table', 'cat' => 3, 'price' => 12000000, 'img' => 'https://images.unsplash.com/photo-1572025442646-866d16c84a54?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'A4 Paper (1 Box)', 'cat' => 2, 'price' => 250000, 'img' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Whiteboard Markers (Set)', 'cat' => 2, 'price' => 150000, 'img' => 'https://images.unsplash.com/photo-1580569214296-5cf2bffc5ced?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Stapler Heavy Duty', 'cat' => 2, 'price' => 85000, 'img' => 'https://images.unsplash.com/photo-1585834015694-a95ab2423376?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'],
        ];

        // Generate up to 40 products
        for ($i = count($products); $i < 40; $i++) {
            $cat = rand(1, 3);
            $products[] = [
                'name' => 'Generic Product ' . ($i + 1),
                'cat' => $cat,
                'price' => rand(100, 5000) * 1000,
                'img' => 'https://images.unsplash.com/photo-1587293852726-59cd15a77413?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
            ];
        }

        foreach ($products as $idx => $p) {
            DB::table('products')->insert([
                'sku' => 'PRD-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'category_id' => $p['cat'],
                'name' => $p['name'],
                'unit' => 'pcs',
                'standard_price' => $p['price'],
                'stock' => rand(10, 100),
                'image' => $p['img'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Generate Workflows (PR -> PO -> GR -> Invoice -> Payment)
        $statuses = ['Draft', 'Submitted', 'Approved', 'PO Created', 'Closed'];

        for ($i = 1; $i <= 30; $i++) {
            $status = $statuses[array_rand($statuses)];
            $reqDate = Carbon::now()->subDays(rand(5, 60));
            $reqId = rand(2, 10);
            
            $prId = DB::table('purchase_requests')->insertGetId([
                'pr_number' => 'PR-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'requester_id' => $reqId,
                'department_id' => rand(1, 6),
                'request_date' => $reqDate,
                'required_date' => $reqDate->copy()->addDays(rand(7, 30)),
                'priority' => ['Normal', 'High', 'Urgent'][array_rand(['Normal', 'High', 'Urgent'])],
                'purpose' => 'Procurement for operational needs part ' . $i,
                'status' => $status,
                'total_amount' => 0, // Will update
                'created_at' => $reqDate,
                'updated_at' => $reqDate,
            ]);

            $itemCount = rand(1, 5);
            $totalAmount = 0;
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products[rand(0, 39)];
                $qty = rand(1, 20);
                $sub = $product['price'] * $qty;
                $totalAmount += $sub;

                DB::table('purchase_request_items')->insert([
                    'purchase_request_id' => $prId,
                    'product_id' => array_search($product, $products) + 1,
                    'quantity' => $qty,
                    'estimated_price' => $product['price'],
                    'subtotal' => $sub,
                    'created_at' => $reqDate,
                    'updated_at' => $reqDate,
                ]);
            }
            DB::table('purchase_requests')->where('id', $prId)->update(['total_amount' => $totalAmount]);

            // If Approved or beyond
            if (in_array($status, ['Approved', 'PO Created', 'Closed'])) {
                DB::table('approvals')->insert([
                    'approvable_type' => 'App\Models\PurchaseRequest',
                    'approvable_id' => $prId,
                    'approver_id' => 4, // Manager
                    'status' => 'Approved',
                    'comments' => 'Looks good, approved.',
                    'created_at' => $reqDate->copy()->addDays(1),
                    'updated_at' => $reqDate->copy()->addDays(1),
                ]);
            }

            // Generate PO
            if (in_array($status, ['PO Created', 'Closed'])) {
                $poDate = $reqDate->copy()->addDays(2);
                $vendorId = rand(1, 10);
                $poId = DB::table('purchase_orders')->insertGetId([
                    'po_number' => 'PO-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'purchase_request_id' => $prId,
                    'vendor_id' => $vendorId,
                    'created_by' => 3, // Procurement
                    'order_date' => $poDate,
                    'expected_delivery' => $poDate->copy()->addDays(7),
                    'amount' => $totalAmount,
                    'tax' => $totalAmount * 0.11,
                    'grand_total' => $totalAmount * 1.11,
                    'status' => $status == 'Closed' ? 'Completed' : 'Sent',
                    'created_at' => $poDate,
                    'updated_at' => $poDate,
                ]);

                // PO Items
                $prItems = DB::table('purchase_request_items')->where('purchase_request_id', $prId)->get();
                foreach ($prItems as $pri) {
                    DB::table('purchase_order_items')->insertGetId([
                        'purchase_order_id' => $poId,
                        'product_id' => $pri->product_id,
                        'quantity' => $pri->quantity,
                        'unit_price' => $pri->estimated_price,
                        'subtotal' => $pri->subtotal,
                        'created_at' => $poDate,
                        'updated_at' => $poDate,
                    ]);
                }

                // If Closed, Generate GR, Invoice, Payment
                if ($status == 'Closed') {
                    $grDate = $poDate->copy()->addDays(5);
                    $grId = DB::table('goods_receipts')->insertGetId([
                        'gr_number' => 'GR-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'purchase_order_id' => $poId,
                        'receiver_id' => 5, // Warehouse
                        'receipt_date' => $grDate,
                        'status' => 'Received',
                        'created_at' => $grDate,
                        'updated_at' => $grDate,
                    ]);

                    $poItems = DB::table('purchase_order_items')->where('purchase_order_id', $poId)->get();
                    foreach ($poItems as $poi) {
                        DB::table('goods_receipt_items')->insert([
                            'goods_receipt_id' => $grId,
                            'purchase_order_item_id' => $poi->id,
                            'product_id' => $poi->product_id,
                            'quantity_ordered' => $poi->quantity,
                            'quantity_received' => $poi->quantity,
                            'created_at' => $grDate,
                            'updated_at' => $grDate,
                        ]);
                    }

                    $invDate = $grDate->copy()->addDays(1);
                    $invId = DB::table('invoices')->insertGetId([
                        'invoice_number' => 'INV-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'vendor_id' => $vendorId,
                        'purchase_order_id' => $poId,
                        'goods_receipt_id' => $grId,
                        'verified_by' => 6, // Finance
                        'invoice_date' => $invDate,
                        'due_date' => $invDate->copy()->addDays(30),
                        'amount' => $totalAmount * 1.11,
                        'verification_status' => 'Matched',
                        'payment_status' => 'Paid',
                        'created_at' => $invDate,
                        'updated_at' => $invDate,
                    ]);

                    $payDate = $invDate->copy()->addDays(10);
                    DB::table('payments')->insert([
                        'payment_number' => 'PAY-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                        'invoice_id' => $invId,
                        'processed_by' => 6,
                        'payment_date' => $payDate,
                        'amount' => $totalAmount * 1.11,
                        'payment_method' => 'Bank Transfer',
                        'reference_number' => 'REF-' . rand(100000, 999999),
                        'created_at' => $payDate,
                        'updated_at' => $payDate,
                    ]);
                }
            }
        }
    }
}
