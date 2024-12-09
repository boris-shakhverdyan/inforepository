<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Sereny\NovaPermissions\Nova\Role as BaseRole;

class Role extends BaseRole
{
    public function authorizedToReplicate(Request $request): false
    {
        return false;
    }
}
