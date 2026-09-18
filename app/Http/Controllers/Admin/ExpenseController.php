<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderByDesc('expense_date')->orderByDesc('id')->get();

        $summary = [
            'total_expenses' => Expense::sum('amount'),
            'month_expenses' => Expense::where('expense_date', '>=', now()->startOfMonth())->sum('amount'),
        ];

        $categories = Expense::selectRaw('category, sum(amount) as total, count(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.expenses.index', compact('expenses', 'summary', 'categories'));
    }

    public function create()
    {
        $expense = null;
        $suggestedCategories = ['Supplies', 'Utilities', 'Rent', 'Labor', 'Marketing', 'Equipment', 'Food & Drinks', 'Maintenance', 'Transportation', 'Other'];

        return view('admin.expenses.form', compact('expense', 'suggestedCategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        $suggestedCategories = ['Supplies', 'Utilities', 'Rent', 'Labor', 'Marketing', 'Equipment', 'Food & Drinks', 'Maintenance', 'Transportation', 'Other'];

        return view('admin.expenses.form', compact('expense', 'suggestedCategories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $this->validateExpense($request);

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted.');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);
    }
}