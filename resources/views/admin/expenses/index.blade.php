@extends('admin.layout')
@section('title', 'Expenses')

@section('content')
<div class="an-page" style="padding:28px 32px;min-height:calc(100vh - 64px);">

    {{-- Header --}}
    <div class="an-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:12px;">
            <h1 style="font-size:26px;font-weight:800;color:#2C221E;letter-spacing:-0.4px;">Expenses</h1>
            <span class="an-date" style="font-size:13px;color:#7A6E65;">{{\Carbon\Carbon::today()->format('M d, Y')}}</span>
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary btn-sm">+ Record Expense</a>
    </div>

    {{-- Summary cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon danger">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label">Total Expenses</div>
            <div class="stat-value">₱{{ number_format($summary['total_expenses'], 2) }}</div>
            <div class="stat-change">All time</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-label">This Month</div>
            <div class="stat-value">₱{{ number_format($summary['month_expenses'], 2) }}</div>
            <div class="stat-change">Since {{ now()->startOfMonth()->format('M d') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="stat-label">Expense Records</div>
            <div class="stat-value">{{ $expenses->count() }}</div>
            <div class="stat-change">Entries logged</div>
        </div>
    </div>

    {{-- Breakdown + list --}}
    <div class="an-grid" style="display:grid;grid-template-columns:5fr 7fr;gap:16px;align-items:start;">
        {{-- By category --}}
        <div class="an-card">
            <div class="an-card-hd" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                <span class="an-label">Spending by Category</span>
                <span class="an-pill">{{ $categories->count() }} categories</span>
            </div>
            <div class="an-pp" style="display:flex;flex-direction:column;gap:10px;">
                @forelse($categories as $cat)
                <div class="an-pp-item" style="display:flex;align-items:center;gap:12px;">
                    <div class="an-pp-icon" style="width:34px;height:34px;border-radius:10px;background:rgba(194,155,56,0.14);color:#A37B2C;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <div class="an-pp-info" style="flex:1;min-width:0;">
                        <div class="pp-name" style="font-size:13px;font-weight:700;color:#2C221E;">{{ $cat->category }}</div>
                        <div class="pp-handle" style="font-size:11px;color:#7A6E65;">{{ $cat->count }} {{ Str::plural('entry', $cat->count) }}</div>
                    </div>
                    <span class="an-pp-count" style="min-width:28px;min-height:28px;padding:0 8px;background:#F0EAE1;color:#2C221E;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">₱{{ number_format($cat->total, 2) }}</span>
                </div>
                @empty
                <div class="an-pp-item">
                    <div class="an-pp-info">
                        <div class="pp-name" style="font-size:13px;font-weight:700;color:#2C221E;">No expenses yet</div>
                        <div class="pp-handle" style="font-size:11px;color:#7A6E65;">Record your first expense to see a breakdown.</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- List --}}
        <div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                        <tr>
                            <td style="white-space:nowrap;">{{ $expense->expense_date->format('M d, Y') }}</td>
                            <td>
                                <div style="font-weight:600;color:var(--gray-900);">{{ $expense->title }}</div>
                                @if($expense->notes)
                                <div style="font-size:12px;color:var(--gray-400);">{{ $expense->notes }}</div>
                                @endif
                            </td>
                            <td>
                                @if($expense->category)
                                <span class="badge badge-info">{{ $expense->category }}</span>
                                @else
                                <span class="badge badge-neutral">Uncategorized</span>
                                @endif
                            </td>
                            <td style="font-weight:700;color:var(--gray-900);white-space:nowrap;">₱{{ number_format($expense->amount, 2) }}</td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:flex-end;">
                                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="icon">🧾</div>
                                    <p>No expenses recorded yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection