<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Payement;
use App\Models\SubscriptionPlan;
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
            "name" => "Admin",
            'email' => 'admin@gerexatlas.com',
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
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
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
            'password' => bcrypt('password'),
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

        Unit::create([
            'building_id' => 1,
            'name' => 'Unit 1',
            'surface' => '100m2',
            'type' => 'Flat',
            'reference' => 'UNIT123',
            'status' => 'available',
        ]);

        Unit::create([
            'building_id' => 2,
            'name' => 'Unit 2',
            'surface' => '200m2',
            'type' => 'Apartment',
            'reference' => 'UNIT456',
            'status' => 'available',
        ]);

        Tenant::create([
            'unit_id' => 1,
            'name' => 'Tenant 1',
            'email' => 'tenant1@example.com',
            'reference' => 'TENANT123',
            'status' => 'active',
        ]);

        Tenant::create([
            'unit_id' => 2,
            'name' => 'Tenant 2',
            'email' => 'tenant2@example.com',
            'phone' => '123-456-7890',
            'reference' => 'TENANT456',
            'status' => 'active',
        ]);

        Contract::create([
            'tenant_id' => 1,
            'unit_id' => 1,
            'contract_type' => 'Lease',
            'contract_body' => 'This is a sample contract body.',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1000,
            'reference' => 'CONTRACT12',
            'status' => 'active',
        ]);
        Contract::create([
            'tenant_id' => 2,
            'unit_id' => 2,
            'contract_type' => 'hehe boi',
            'contract_body' => 'contract body',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1200,
            'reference' => 'CONTRACT123',
            'status' => 'active',
        ]);

        // Invoice::create([
        //     'tenant_id' => 1,
        //     'unit_id' => 1,
        //     'month' => 1,
        //     'status' => 'pending',
        //     'invoice_body' => 'Invoice body for month 1',
        //     'amount' => 1000.00,
        //     // 'due_date' => now()->addMonth(),
        //     'reference' => 'INVOICE123',
        // ]);
        // Invoice::create([
        //     'tenant_id' => 2,
        //     'unit_id' => 2,
        //     'month' => 2,
        //     'status' => 'pending',
        //     'invoice_body' => 'Invoice body for month 2',
        //     'amount' => 1200.00,
        //     // 'due_date' => now()->addMonth(),
        //     'reference' => 'INVOICE456',
        // ]);

        Payement::create([
            'amount' => 1000,
            'unit_id' => 1,
            'tenant_id' => 1,
            'receipt' => 'hehe boi',
            'payement_method' => 'credit_card',
            'reference' => 'PAYMENT123',
            'status' => 'completed',
        ]);

        Payement::create([
            'amount' => 1200,
            'unit_id' => 2,
            'tenant_id' => 2,
            'receipt' => 'ehbfebfe',
            'payement_method' => 'orange money',
            'reference' => 'PAYMENT456',
            'status' => 'completed',
        ]);
    }
}
