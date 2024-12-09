<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Sereny\NovaPermissions\Nova\Permission as BasePermission;

class Permission extends BasePermission
{
    public function authorizedToReplicate(Request $request): false
    {
        return false;
    }
}
