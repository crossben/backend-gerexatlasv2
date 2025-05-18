<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Contract;
use App\Models\Manager;
use App\Models\Payement;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            "name" => "Ben hattab Mahdi",
            'email' => 'ben@gerexatlas.com',
            'password' => '12334567',
            'role' => 'ultra_admin',
            'status' => 'active',
        ]);

        User::create([
            "name" => "Ben hattab Mahdi",
            'email' => 'ben2@gerexatlas.com',
            'password' => '12334567',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // SubscriptionPlan::create([
        //     'name' => 'Basic Plan',
        //     'max_buildings' => 5,
        //     'price' => 10.00,
        //     'duration' => 30,
        //     'reference' => 'BASIC123',
        //     'status' => 'active',
        // ]);

        // SubscriptionPlan::create([
        //     'name' => 'Premium Plan',
        //     'max_buildings' => 10,
        //     'price' => 20.00,
        //     'duration' => 30,
        //     'reference' => 'PREMIUM456',
        //     'status' => 'active',
        // ]);

        // Organization::create([
        //     'subscription_plan_id' => 1,
        //     'name' => 'Test Organization 1',
        //     'created_by_user_id' => 1,
        //     'description' => 'A description of the test organization',
        //     'reference' => 'ORG123',
        //     'status' => 'active',
        // ]);

        // Organization::create([
        //     'subscription_plan_id' => 1,
        //     'name' => 'Test Organization 2',
        //     'created_by_user_id' => 1,
        //     'description' => 'A description of the second test organization',
        //     'reference' => 'ORG456',
        //     'status' => 'active',
        // ]);

        Manager::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@gmail.com',
            'phone' => '1234567890',
            'password' => \Illuminate\Support\Facades\Hash::make('12334567'),
            'role' => 'manager',
            'status' => 'active',
            'reference' => 'MANAGER123',
            'address' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
        ]);
        Manager::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '0987654321',
            'password' => \Illuminate\Support\Facades\Hash::make('12334567'),
            'role' => 'manager',
            'status' => 'active',
            'reference' => 'MANAGER456',
            'address' => '456 Elm St',
            'city' => 'Los Angeles',
            'country' => 'USA',
        ]);

        Building::create([
            'manager_id' => 1,
            'name' => 'Building 1',
            'city' => 'New York',
            'type' => 'Flat',
            'number_of_units' => 5,
            'address' => '123 Main St',
            'description' => 'A description of Building 1',
            'reference' => 'BUILD123',
            'status' => 'active',
        ]);

        Building::create([
            'manager_id' => 2,
            'name' => 'Building 2',
            'city' => 'Los Angeles',
            'type' => 'Apartment',
            'number_of_units' => 10,
            'address' => '456 Elm St',
            'description' => 'A description of Building 2',
            'reference' => 'BUILD456',
            'status' => 'active',
        ]);
        Building::create([
            'manager_id' => 1,
            'name' => 'Building 3',
            'city' => 'Chicago',
            'type' => 'Condo',
            'number_of_units' => 8,
            'address' => '789 Oak St',
            'description' => 'A description of Building 3',
            'reference' => 'BUILD789',
            'status' => 'active',
        ]);
        Building::create([
            'manager_id' => 2,
            'name' => 'Building 4',
            'city' => 'Houston',
            'type' => 'Townhouse',
            'number_of_units' => 12,
            'address' => '101 Pine St',
            'description' => 'A description of Building 4',
            'reference' => 'BUILD101',
            'status' => 'active',
        ]);

        Unit::create([
            'building_id' => 1,
            'manager_id' => 1,
            'name' => 'Unit 1',
            'type' => 'Commercial',
            'tenant_name' => 'John Doe',
            'tenant_email' => 'john.doe@example.com',
            'tenant_phone' => '123-456-7890',
            'start_date' => '2023-01-01',
            'end_date' => '2024-01-01',
            'rent_amount' => 1200.00,
            'contract_type' => 'Yearly',
            'reference' => 'UNIT123',
            'status' => 'available',
        ]);

        Unit::create([
            'building_id' => 2,
            'manager_id' => 1,
            'name' => 'Unit 2',
            'type' => 'Commercial',
            'tenant_name' => 'Jane Smith',
            'tenant_email' => 'jane.smith@example.com',
            'tenant_phone' => '234-567-8901',
            'start_date' => '2023-02-01',
            'end_date' => '2024-02-01',
            'rent_amount' => 1500.00,
            'contract_type' => 'Yearly',
            'reference' => 'UNIT456',
            'status' => 'rented',
        ]);

        Unit::create([
            'building_id' => 3,
            'manager_id' => 1,
            'name' => 'Unit 3',
            'type' => 'Condo',
            'tenant_name' => 'Mark Johnson',
            'tenant_email' => 'mark.johnson@example.com',
            'tenant_phone' => '345-678-9012',
            'start_date' => '2023-03-01',
            'end_date' => '2024-03-01',
            'rent_amount' => 1800.00,
            'contract_type' => 'Yearly',
            'reference' => 'UNIT789',
            'status' => 'under_maintenance',
        ]);

        Unit::create([
            'building_id' => 4,
            'manager_id' => 2,
            'name' => 'Unit 4',
            'type' => 'Townhouse',
            'tenant_name' => 'Alice Williams',
            'tenant_email' => 'alice.williams@example.com',
            'tenant_phone' => '456-789-0123',
            'start_date' => '2023-04-01',
            'end_date' => '2024-04-01',
            'rent_amount' => 1300.00,
            'contract_type' => 'Monthly',
            'reference' => 'UNIT101',
            'status' => 'available',
        ]);

        Unit::create([
            'building_id' => 1,
            'manager_id' => 2,
            'name' => 'Unit 5',
            'type' => 'Flat',
            'tenant_name' => 'David Brown',
            'tenant_email' => 'david.brown@example.com',
            'tenant_phone' => '567-890-1234',
            'start_date' => '2023-05-01',
            'end_date' => '2024-05-01',
            'rent_amount' => 950.00,
            'contract_type' => 'Monthly',
            'reference' => 'UNIT102',
            'status' => 'rented',
        ]);

        Unit::create([
            'building_id' => 2,
            'manager_id' => 2,
            'name' => 'Unit 6',
            'type' => 'Apartment',
            'tenant_name' => 'Emily Davis',
            'tenant_email' => 'emily.davis@example.com',
            'tenant_phone' => '678-901-2345',
            'start_date' => '2023-06-01',
            'end_date' => '2024-06-01',
            'rent_amount' => 1400.00,
            'contract_type' => 'Yearly',
            'reference' => 'UNIT103',
            'status' => 'available',
        ]);

        Unit::create([
            'building_id' => 3,
            'manager_id' => 2,
            'name' => 'Unit 7',
            'type' => 'Condo',
            'tenant_name' => 'Sophia White',
            'tenant_email' => 'sophia.white@example.com',
            'tenant_phone' => '789-012-3456',
            'start_date' => '2023-07-01',
            'end_date' => '2024-07-01',
            'rent_amount' => 1600.00,
            'contract_type' => 'Monthly',
            'reference' => 'UNIT104',
            'status' => 'under_maintenance',
        ]);

        Unit::create([
            'building_id' => 4,
            'manager_id' => 2,
            'name' => 'Unit 8',
            'type' => 'Flat',
            'tenant_name' => 'Chris Green',
            'tenant_email' => 'chris.green@example.com',
            'tenant_phone' => '890-123-4567',
            'start_date' => '2023-08-01',
            'end_date' => '2024-08-01',
            'rent_amount' => 1700.00,
            'contract_type' => 'Yearly',
            'reference' => 'UNIT105',
            'status' => 'rented',
        ]);

        Tenant::create([
            'unit_id' => 1,
            'manager_id' => 1,
            'name' => 'Tenant 1',
            'email' => 'tenant1@example.com',
            'phone' => '123-456-7890',
            'nationality' => 'Puerto Rican',
            'reference' => 'TENANT123',
            'status' => 'inactive',
        ]);

        Tenant::create([
            'unit_id' => 2,
            'manager_id' => 1,
            'name' => 'Tenant 2',
            'email' => 'tenant2@example.com',
            'phone' => '123-456-7890',
            'nationality' => 'Nigerian',
            'reference' => 'TENANT456',
            'status' => 'active',
        ]);
        Tenant::create([
            'unit_id' => 3,
            'manager_id' => 1,
            'name' => 'Tenant 3',
            'email' => 'tenant3@example.com',
            'phone' => '123-456-7890',
            'nationality' => 'American',
            'reference' => 'TENANT789',
            'status' => 'active',
        ]);
        Tenant::create([
            'unit_id' => 4,
            'manager_id' => 2,
            'name' => 'Tenant 4',
            'email' => 'tenant4@example.com',
            'phone' => '123-456-7890',
            'nationality' => 'Indian',
            'reference' => 'TENANT101',
            'status' => 'active',
        ]);

        Contract::create([
            'tenant_id' => 1,
            'unit_id' => 1,
            'manager_id' => 1,
            'contract_type' => 'Lease',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1000,
            'reference' => 'CONTRACT12',
            'status' => 'active',
        ]);
        Contract::create([
            'tenant_id' => 2,
            'unit_id' => 2,
            'manager_id' => 1,
            'contract_type' => 'hehe boi',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1200,
            'reference' => 'CONTRACT123',
            'status' => 'active',
        ]);
        Contract::create([
            'tenant_id' => 3,
            'unit_id' => 3,
            'manager_id' => 1,
            'contract_type' => 'Lease',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1500,
            'reference' => 'CONTRACT456',
            'status' => 'active',
        ]);
        Contract::create([
            'tenant_id' => 4,
            'unit_id' => 4,
            'manager_id' => 1,
            'contract_type' => 'Lease',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1800,
            'reference' => 'CONTRACT789',
            'status' => 'active',
        ]);

        // Invoice::create([
        //     'tenant_id' => 1,
        //     'unit_id' => 1,
        //     'month' => 1,
        //     'status' => 'pending',
        //     'invoice_body' => 'Invoice body for month 1',
        //     'amount' => 1000.00,
        //     'due_date' => now()->addMonth(),
        //     'reference' => 'INVOICE123',
        // ]);
        // Invoice::create([
        //     'tenant_id' => 2,
        //     'unit_id' => 2,
        //     'month' => 2,
        //     'status' => 'pending',
        //     'invoice_body' => 'Invoice body for month 2',
        //     'amount' => 1200.00,
        //     'due_date' => now()->addMonth(),
        //     'reference' => 'INVOICE456',
        // ]);

        Payement::create([
            'amount' => 1000,
            'unit_id' => 1,
            'manager_id' => 1,
            'building_id' => 1,
            // 'receipt' => 'hehe boi',
            'payement_method' => 'credit_card',
            'reference' => 'PAYMENT123',
            'status' => 'completed',
        ]);

        Payement::create([
            'amount' => 1200,
            'unit_id' => 2,
            'manager_id' => 1,
            'building_id' => 2,
            // 'receipt' => 'ehbfebfe',
            'payement_method' => 'bank_transfer',
            'reference' => 'PAYMENT451',
            'status' => 'failed',
        ]);

        Payement::create([
            'amount' => 1200,
            'unit_id' => 2,
            'manager_id' => 1,
            'building_id' => 2,
            // 'receipt' => 'ehbfebfe',
            'payement_method' => 'cash',
            'reference' => 'PAYMENT456',
            'status' => 'pending',
        ]);
        Payement::create([
            'amount' => 1200,
            'unit_id' => 2,
            'manager_id' => 2,
            'building_id' => 2,
            // 'receipt' => 'ehbfebfe',
            'payement_method' => 'credit_card',
            'reference' => 'PAYMENT458',
            'status' => 'completed',
        ]);
        Payement::create([
            'amount' => 1200,
            'unit_id' => 2,
            'manager_id' => 2,
            'building_id' => 2,
            // 'receipt' => 'ehbfebfe',
            'payement_method' => 'bank_transfer',
            'reference' => 'PAYMENT457',
            'status' => 'completed',
        ]);
    }
}
