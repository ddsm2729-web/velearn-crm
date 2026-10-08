<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Operation Executive Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full dark:bg-amber-900 dark:text-amber-200">
                Operation Executive
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Classroom Schedules</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">24</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Resource Utilization</div>
                    <div class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">91%</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Requests</div>
                    <div class="mt-2 text-3xl font-bold text-rose-600 dark:text-rose-400">7</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Operational Uptime</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">100%</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Operations & Facility Logistics</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Manage daily operational logistics, class schedules, physical & digital venue allocations, and facility assets.</p>
            </div>
        </div>
    </div>
</x-app-layout>
