<?php
// database/seeders/DefaultDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\PaymentMethod;
use App\Models\ProductType;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin User
        $superAdminRole = Role::where('name', 'super_admin')->first();
        User::create([
            'email' => 'admin@sakta.com',
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        // Create Default Payment Methods
        $paymentMethods = [
            ['name' => 'Cash', 'code' => 'CASH', 'description' => 'Cash payment'],
            ['name' => 'Bank Transfer', 'code' => 'TRANSFER', 'description' => 'Bank transfer payment'],
            ['name' => 'Credit Card', 'code' => 'CREDIT_CARD', 'description' => 'Credit card payment'],
            ['name' => 'Debit Card', 'code' => 'DEBIT_CARD', 'description' => 'Debit card payment'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create(['code' => $method['code']], $method);
        }

        // Create Default Product Types
        $productTypes = [
            ['name' => 'Service', 'code' => 'SERVICE', 'description' => 'Service products'],
            ['name' => 'Physical Product', 'code' => 'PHYSICAL', 'description' => 'Physical products'],
            ['name' => 'Digital Product', 'code' => 'DIGITAL', 'description' => 'Digital products'],
            ['name' => 'Subscription', 'code' => 'SUBSCRIPTION', 'description' => 'Subscription products'],
        ];

        foreach ($productTypes as $type) {
            ProductType::create(['code' => $type['code']], $type);
        }

        // Create Default Product Units
        $productUnits = [
            ['name' => 'Piece', 'symbol' => 'pcs', 'description' => 'Per piece'],
            ['name' => 'Hour', 'symbol' => 'hr', 'description' => 'Per hour'],
            ['name' => 'Day', 'symbol' => 'day', 'description' => 'Per day'],
            ['name' => 'Month', 'symbol' => 'mo', 'description' => 'Per month'],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'description' => 'Per kilogram'],
            ['name' => 'Liter', 'symbol' => 'L', 'description' => 'Per liter'],
            ['name' => 'Meter', 'symbol' => 'm', 'description' => 'Per meter'],
        ];

        foreach ($productUnits as $unit) {
            ProductUnit::create(['symbol' => $unit['symbol']], $unit);
        }
    }
}