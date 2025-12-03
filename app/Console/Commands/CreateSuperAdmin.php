<?php
// app/Console/Commands/CreateSuperAdmin.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:super-admin {--email=} {--password=} {--name=}';
    protected $description = 'Create a super admin user for SAKTA application';

    public function handle()
    {
        $this->info('Creating Super Admin User for SAKTA');
        $this->newLine();

        // Get or prompt for email
        $email = $this->option('email') ?? $this->ask('Enter email address');
        
        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address or email already exists.');
            return 1;
        }

        // Get or prompt for name
        $name = $this->option('name') ?? $this->ask('Enter full name');

        // Get or prompt for password
        $password = $this->option('password') ?? $this->secret('Enter password');

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return 1;
        }

        // Find super admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            $this->error('Super Admin role not found. Please run database seeders first.');
            return 1;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        $this->info("Super Admin user created successfully!");
        $this->info("Email: {$user->email}");
        $this->info("Role: {$superAdminRole->display_name}");
        $this->newLine();
        $this->info("You can now login to the admin panel at /admin");

        return 0;
    }
}