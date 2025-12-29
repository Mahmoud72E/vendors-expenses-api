<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of expenses (with filters).
     */
    public function index(Request $request)
    {
        try {
            $expenses = Expense::query()
                ->with(['category', 'vendor'])
                ->when($request->vendor_id, fn ($q) => $q->whereVendorId($request->vendor_id))
                ->when($request->category_id, fn ($q) => $q->whereCategoryId($request->category_id))
                ->when($request->from, fn ($q) => $q->whereDate('date', '>=', $request->from))
                ->when($request->to, fn ($q) => $q->whereDate('date', '<=', $request->to))
                ->latest()
                ->paginate(10);

            return $this->success($expenses, 'Expenses retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch expenses', 500);
        }
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => [
                    'required',
                    Rule::exists('expense_categories', 'id')->where('is_active', true),
                ],
                'vendor_id'  => 'nullable|exists:vendors,id',
                'amount'     => 'required|numeric|min:0.01',
                'date'       => 'required|date',
                'description'=> 'nullable|string',
            ]);

            $expense = Expense::create([
                ...$validated,
                'created_by' => $request->user()->id,
            ]);

            return $this->success($expense, 'Expense created successfully', 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->error('Failed to create expense', 500);
        }
    }

    /**
     * Display the specified expense.
     */
    public function show(string $id)
    {
        try {
            $expense = Expense::with(['category', 'vendor'])->findOrFail($id);

            return $this->success($expense, 'Expense retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Expense not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch expense', 500);
        }
    }

    /**
     * Update the specified expense (optional).
     */
    public function update(Request $request, string $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            $this->authorize('update', $expense);

            $validated = $request->validate([
                'category_id' => [
                    'sometimes',
                    Rule::exists('expense_categories', 'id')->where('is_active', true),
                ],
                'vendor_id'   => 'nullable|exists:vendors,id',
                'amount'      => 'numeric|min:0.01',
                'date'        => 'date',
                'description' => 'nullable|string',
            ]);

            $expense->update($validated);

            return $this->success($expense, 'Expense updated successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error('Forbidden', 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Expense not found', 404);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return $this->error('Failed to update expense', 500);
        }
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(string $id)
    {
        try {
            $expense = Expense::findOrFail($id);

            $this->authorize('delete', $expense);

            $expense->delete();

            return $this->success(null, 'Expense deleted successfully');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error('Forbidden', 403);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Expense not found', 404);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete expense', 500);
        }
    }

    public function summary(Request $request)
    {
        try {
            $summary = DB::table('expenses')
                ->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
                ->selectRaw('
                    DATE_FORMAT(expenses.date, "%Y-%m") as month,
                    expense_categories.id as category_id,
                    expense_categories.name as category_name,
                    SUM(expenses.amount) as total_amount
                ')
                ->whereNull('expenses.deleted_at')
                ->groupBy('month', 'category_id', 'category_name')
                ->orderBy('month', 'desc')
                ->get();

            return $this->success($summary, 'Summary report retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to generate summary report', 500);
        }
    }
}
