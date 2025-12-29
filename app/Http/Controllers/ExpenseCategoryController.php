<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExpenseCategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        try {
            $categories = ExpenseCategory::latest()->paginate(10);

            return $this->success($categories, 'Categories retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch categories', 500);
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $category = ExpenseCategory::create($validated);

            return $this->success($category, 'Category created successfully', 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->error('Failed to create category', 500);
        }
    }

    /**
     * Display the specified category.
     */
    public function show(string $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            return $this->success($category, 'Category retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Category not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch category', 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            $validated = $request->validate([
                'name'      => 'sometimes|required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $category->update($validated);

            return $this->success($category, 'Category updated successfully');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Category not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to update category', 500);
        }
    }

    /**
     * Remove the specified category.
     * Block delete if linked expenses exist (policy).
     */
    public function destroy(string $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            $this->authorize('delete', $category);

            $category->delete();

            return $this->success(null, 'Category deleted successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error('Category has related expenses', 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Category not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete category', 500);
        }
    }
}
