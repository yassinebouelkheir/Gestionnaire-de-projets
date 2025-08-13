<?php
namespace App\Traits;

use App\Models\User;

trait NotifiableTrait
{
    public function getUsersToNotify()
    {
        $admins = User::where('role', 'admin')->get();
        $assignedUsers = $this->users()->get();
        $creator = $this->creator ? collect([$this->creator]) : collect();

        return $admins->merge($assignedUsers)->merge($creator)->unique('id');
    }
}
