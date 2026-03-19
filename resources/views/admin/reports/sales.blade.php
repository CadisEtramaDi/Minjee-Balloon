@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 style="color: #111827; font-size: 1.875rem; font-weight: 700;">Sales Report</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 rounded-lg font-medium" style="background-color: #4b5563; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payments
            </a>
            <button onclick="window.print()" class="px-4 py-2 rounded-lg font-medium" style="background-color: #0EA5E9; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; border: none; cursor: pointer;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 style="color: #111827; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Filter Report</h2>
    <form method="GET" action="{{ route('admin.reports.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="start_date" style="display: block; color: #374151; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">Start Date</label>
            <input type="date" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent" 
                   id="start_date" 
                   name="start_date" 
                   value="{{ request('start_date') }}">
        </div>
        <div>
            <label for="end_date" style="display: block; color: #374151; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">End Date</label>
            <input type="date" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent" 
                   id="end_date" 
                   name="end_date" 
                   value="{{ request('end_date') }}">
        </div>
        <div>
            <label for="payment_method" style="display: block; color: #374151; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem;">Payment Method</label>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0EA5E9] focus:border-transparent" 
                    id="payment_method" 
                    name="payment_method">
                <option value="">All Methods</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium" style="width: 100%; background-color: #0EA5E9; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Apply Filter
            </button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6" style="background: linear-gradient(to right, #22c55e, #16a34a);">
        <h3 style="color: white; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.125rem;">Total Sales</h3>
        <p style="color: white; font-size: 2.25rem; font-weight: 700;">₱{{ number_format($totalSales, 2) }}</p>
    </div>
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6" style="background: linear-gradient(to right, #3b82f6, #2563eb);">
        <h3 style="color: white; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.125rem;">Total Transactions</h3>
        <p style="color: white; font-size: 2.25rem; font-weight: 700;">{{ $totalTransactions }}</p>
    </div>
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6" style="background: linear-gradient(to right, #a855f7, #9333ea);">
        <h3 style="color: white; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.125rem;">Average Transaction</h3>
        <p style="color: white; font-size: 2.25rem; font-weight: 700;">₱{{ $totalTransactions > 0 ? number_format($totalSales / $totalTransactions, 2) : '0.00' }}</p>
    </div>
</div>

<!-- Sales Trend Line Chart -->
@if($dailySales->count() > 0)
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 style="color: #111827; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Sales Trend</h2>
    <div style="position: relative; height: 300px;">
        <canvas id="salesTrendChart"></canvas>
    </div>
</div>
@endif

<!-- Sales by Payment Method -->
@if($salesByMethod->count() > 0)
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h2 style="color: #111827; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Sales by Payment Method</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(to right, #1f2937, #374151);">
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Payment Method</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Number of Transactions</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Total Amount</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesByMethod as $method => $data)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4" style="color: #111827; font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                    <td class="py-3 px-4" style="color: #111827; font-weight: 600;">{{ $data['count'] }}</td>
                    <td class="py-3 px-4" style="color: #16a34a; font-weight: 700; font-size: 1.125rem;">₱{{ number_format($data['total'], 2) }}</td>
                    <td class="py-3 px-4" style="color: #111827; font-weight: 600;">{{ $totalSales > 0 ? number_format(($data['total'] / $totalSales) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Detailed Transaction List -->
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 style="color: #111827; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Transaction Details</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr style="background: linear-gradient(to right, #1f2937, #374151);">
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Payment ID</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Date</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Booking ID</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Customer</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Payment Method</th>
                    <th class="text-left py-3 px-4" style="color: white; font-weight: 700;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-4" style="color: #111827; font-weight: 700;">#{{ $payment->paymentID }}</td>
                    <td class="py-3 px-4" style="color: #111827; font-weight: 600;">{{ $payment->paymentdate->format('M d, Y') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.bookings.show', $payment->bookingID) }}" style="color: #2563eb; font-weight: 700; text-decoration: underline;">
                            #{{ $payment->bookingID }}
                        </a>
                    </td>
                    <td class="py-3 px-4" style="color: #111827; font-weight: 600;">
                        {{ $payment->booking->customer->fname }} 
                        {{ $payment->booking->customer->lname }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full" style="background-color: #2563eb; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-block;">
                            {{ ucfirst(str_replace('_', ' ', $payment->paymentmethod)) }}
                        </span>
                    </td>
                    <td class="py-3 px-4" style="color: #16a34a; font-weight: 700; font-size: 1.125rem;">₱{{ number_format($payment->amountpaid, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #9ca3af; width: 4rem; height: 4rem; margin-left: auto; margin-right: auto; margin-bottom: 1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p style="color: #6b7280; font-size: 1.125rem; font-weight: 600;">No transactions found for the selected period.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($payments->count() > 0)
            <tfoot>
                <tr style="background-color: #dcfce7; border-top: 2px solid #16a34a;">
                    <th colspan="5" class="text-right py-3 px-4" style="text-align: right; color: #111827; font-weight: 700; font-size: 1rem;">TOTAL:</th>
                    <th class="py-3 px-4" style="color: #16a34a; font-weight: 700; font-size: 1.25rem;">₱{{ number_format($totalSales, 2) }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<style>
@media print {
    .flex.gap-2, nav, aside, button {
        display: none !important;
    }
    body {
        background: white !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@if(isset($dailySales) && $dailySales->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    const salesData = @json($dailySales->reverse()->values());

    const labels = salesData.map(item => {
        const d = new Date(item.date);
        // Correct time zone offset issues by using string parsing if necessary, 
        // but toLocaleDateString usually works fine.
        return d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
    });
    
    const data = salesData.map(item => parseFloat(item.total));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Daily Sales (₱)',
                data: data,
                borderColor: '#0EA5E9',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#0EA5E9',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 13, weight: 'bold' },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.parsed.y.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        font: { size: 12 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 11 }, maxRotation: 45 },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endif
@endsection
