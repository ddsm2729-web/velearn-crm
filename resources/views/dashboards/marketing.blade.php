<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Digital Marketing Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900 dark:text-orange-200">
                Digital Marketing
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Campaigns</div>
                    <div class="mt-2 text-3xl font-bold text-orange-600 dark:text-orange-400">8</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Leads Generated</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">452</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Click-Through Rate (CTR)</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">4.6%</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cost Per Lead (CPL)</div>
                    <div class="mt-2 text-3xl font-bold text-indigo-600 dark:text-indigo-400">₹145</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Marketing Analytics & Lead Inflow</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Monitor campaign ROI, track referral channels, and route incoming leads to the tele-calling team.</p>
            </div>
        </div>
    </div>
</x-app-layout>
