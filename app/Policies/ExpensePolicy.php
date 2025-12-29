<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    /**
     * Admin and staff can view expenses list.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Admin can view any expense.
     * Staff can view only their own expenses.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $user->role === 'admin'
            || $expense->created_by === $user->id;
    }

    /**
     * Admin and staff can create expenses.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Admin can update any expense.
     * Staff can update only their own expenses.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $user->role === 'admin'
            || $expense->created_by === $user->id;
    }

    /**
     * Admin can delete any expense.
     * Staff can delete only their own expenses.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $user->role === 'admin'
            || $expense->created_by === $user->id;
    }

    /**
     * Only admin can restore expenses.
     */
    public function restore(User $user, Expense $expense): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admin can force delete expenses.
     */
    public function forceDelete(User $user, Expense $expense): bool
    {
        return $user->role === 'admin';
    }
}
