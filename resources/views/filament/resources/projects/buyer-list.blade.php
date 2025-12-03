
<div class="space-y-4">
    @if(isset($buyers) && count($buyers) > 0)
        <div>
            @foreach($buyers as $buyer)
                <x-filament::card>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                    <x-filament::icon 
                                        icon="heroicon-o-user" 
                                        class="w-5 h-5 text-primary-600 dark:text-primary-400"
                                    />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $buyer['name'] }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <x-filament::icon 
                                            icon="heroicon-o-check-circle" 
                                            class="w-4 h-4 text-success-500"
                                        />
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Verified</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="items-center justify-center px-3 py-1 text-sm font-bold text-warning-700 bg-warning-100 rounded-full dark:bg-warning-900 dark:text-warning-300">
                                    {{ $buyer['percentage'] }}%
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Presentase</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $buyer['percentage'] }}%</span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Pembelian</span>
                                <span class="text-sm font-bold text-primary-600 dark:text-primary-400">
                                    Rp{{ number_format($buyer['total_amount'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-filament::card>
                

            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <x-filament::icon 
                icon="heroicon-o-users" 
                class="w-12 h-12 mx-auto mb-2 opacity-50"
            />
            <p>Belum ada buyer untuk project ini</p>
        </div>
    @endif
</div>
