<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Admin and staff can view categories.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Admin and staff can view a category.
     */
    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Only admin can create categories.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admin can update categories.
     */
    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Admin can delete category ONLY if no expenses exist.
     */
    public function delete(User $user, ExpenseCategory $category): bool
    {
        return $user->role === 'admin'
            && !$category->expenses()->exists();
    }

    /**
     * Only admin can restore categories.
     */
    public function restore(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admin can force delete categories.
     */
    public function forceDelete(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->role === 'admin';
    }
}
