<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [Role::ADMIN];

        foreach ($roles as $role) {
            Role::create(["name" => $role]);
        }

        User::findByEmail(User::ADMIN_EMAIL)?->assignRole(Role::ADMIN);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::findByEmail(User::ADMIN_EMAIL)?->removeRole(Role::ADMIN);

        Role::findByName(Role::ADMIN)?->delete();
    }
};
