@php
    $total = $record->qty ?? 100;
    $sold = $record->sold_qty ?? 60;
    $remaining = $total - $sold;
    $percentage = $total > 0 ? round(($sold / $total) * 100) : 0;
    
    $totalSales = $record->total_sales ?? 500000000;
    $totalMargin = $record->total_margin ?? 5500000;
@endphp

<div style="padding: 1.5rem 0;">
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Produk Terjual -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background-color: #f0fdf4; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 24px; height: 24px; color: #22c55e;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Produk Terjual</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #111827;">{{ number_format($sold, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <!-- Produk Tersisa -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background-color: #eff6ff; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 24px; height: 24px; color: #3b82f6;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <div>
                <div style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">Produk Tersisa</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #111827;">{{ number_format($remaining, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div style="margin-bottom: 1.5rem;">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">{{ $percentage }}% Dari estimasi</div>
        <div style="width: 100%; height: 16px; background-color: #d1d5db; border-radius: 9999px; overflow: hidden; position: relative;">
            <div style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $percentage }}%; background: linear-gradient(90deg, #fcd34d 0%, #fbbf24 50%, #f59e0b 100%); border-radius: 9999px; transition: width 0.5s ease;"></div>
        </div>
    </div>
    
    <!-- Sales Summary -->
    <div style="background-color: #f9fafb; border-radius: 0.5rem; padding: 1.25rem; border: 1px solid #e5e7eb;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
            <span style="font-size: 1rem; font-weight: 500; color: #111827;">Total Penjualan</span>
            <span style="font-size: 1.25rem; font-weight: 700; color: #111827;">Rp{{ number_format($totalSales, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1rem; font-weight: 500; color: #06b6d4;">— Total Margin</span>
            <span style="font-size: 1.25rem; font-weight: 700; color: #111827;">Rp{{ number_format($totalMargin, 0, ',', '.') }}</span>
        </div>
    </div>
</div>