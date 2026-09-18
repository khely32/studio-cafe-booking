@extends('admin.layout')
@section('title', isset($expense) ? 'Edit Expense' : 'New Expense')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($expense) ? 'Edit Expense' : 'New Expense' }}</div>
        <div class="page-subtitle">{{ isset($expense) ? 'Update the expense details below.' : 'Fill in the details for the expense.' }}</div>
    </div>
</div>

<div style="max-width:720px;background:linear-gradient(135deg,rgba(250,248,245,0.9),rgba(250,248,245,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(250,248,245,0.5);border-radius:var(--radius);padding:28px;box-shadow:0 10px 40px rgba(139,111,71,0.08);">
    <div>
        <form method="POST" action="{{ isset($expense) ? route('admin.expenses.update', $expense) : route('admin.expenses.store') }}">
            @csrf
            @if(isset($expense)) @method('PUT') @endif

            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" value="{{ old('title', $expense->title ?? '') }}" class="form-control" required placeholder="e.g. Coffee beans restock, Electricity bill">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" value="{{ old('category', $expense->category ?? '') }}" class="form-control" list="expense-categories" placeholder="e.g. Supplies">
                    <datalist id="expense-categories">
                        @foreach($suggestedCategories as $cat)
                        <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Amount (₱) *</label>
                    <input type="number" name="amount" value="{{ old('amount', $expense->amount ?? 0) }}" class="form-control" required min="0" step="0.01" placeholder="0.00">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', isset($expense) ? $expense->expense_date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <input type="text" name="payment_method" value="{{ old('payment_method', $expense->payment_method ?? '') }}" class="form-control" list="expense-payments" placeholder="e.g. Cash, GCash, Bank">
                    <datalist id="expense-payments">
                        @foreach(['Cash', 'GCash', 'PayMaya', 'Bank Transfer', 'Credit Card'] as $pm)
                        <option value="{{ $pm }}"></option>
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" placeholder="Optional details, receipt number, vendor, etc.">{{ old('notes', $expense->notes ?? '') }}</textarea>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid rgba(0,0,0,0.06);">
                <button type="submit" class="btn btn-primary">{{ isset($expense) ? 'Update Expense' : 'Save Expense' }}</button>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection