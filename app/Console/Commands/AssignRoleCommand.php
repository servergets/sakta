<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    protected $signature = 'user:assign-role {email?} {role?}';

    protected $description = 'Assign role to user';

    public function handle()
    {
        // Get email
        $email = $this->argument('email') ?? $this->ask('User email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User not found: {$email}");
            return 1;
        }

        // Show user info
        $this->info("👤 User: {$user->name} ({$user->email})");
        $this->info("📋 Current Roles: " . ($user->getRoleNames()->implode(', ') ?: 'None'));
        
        // Get available roles
        $roles = Role::all()->pluck('name')->toArray();
        
        // Get role
        $role = $this->argument('role') ?? $this->choice(
            'Select role to assign',
            $roles
        );
        
        if (!in_array($role, $roles)) {
            $this->error("❌ Role not found: {$role}");
            return 1;
        }

        // Assign role
        if ($user->hasRole($role)) {
            $this->warn("⚠️  User already has role: {$role}");
        } else {
            $user->assignRole($role);
            $this->info("✅ Role '{$role}' assigned to {$user->name}");
        }

        // Show updated roles
        $this->info("📋 Updated Roles: " . $user->getRoleNames()->implode(', '));
        
        // Show permissions
        $permissions = $user->getAllPermissions()->pluck('name');
        $this->info("🔑 Total Permissions: " . $permissions->count());
        
        if ($this->confirm('Show all permissions?', false)) {
            $this->table(['Permission'], $permissions->map(fn($p) => [$p])->toArray());
        }

        return 0;
    }
}