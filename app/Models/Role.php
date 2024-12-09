<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

/**
 * @property-read bool $is_for_system
 */
class Role extends BaseRole
{
    const ADMIN = "admin";

    public static array $systemRoles = [
        self::ADMIN,
    ];

    public function getIsForSystemAttribute(): bool
    {
        return in_array($this->name, self::$systemRoles);
    }
}
