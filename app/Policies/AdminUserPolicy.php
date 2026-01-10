<?php

namespace App\Policies;

use App\Models\AdminUser;

class AdminUserPolicy
{
    public function manageAdmins(?AdminUser $user): bool
    {
        return $user !== null && $user->canManageAdmins();
    }
}
