<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Coordinator Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-violet-800 bg-violet-100 rounded-full dark:bg-violet-900 dark:text-violet-200">
                Student Coordinator
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Enrolled Students</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">320</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Batches</div>
                    <div class="mt-2 text-3xl font-bold text-violet-600 dark:text-violet-400">12</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">92%</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Grievances Pending</div>
                    <div class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">5</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Student Batch & Welfare Coordination</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Coordinate batch timetables, monitor student attendance, resolve student grievances, and issue course certificates.</p>
            </div>
        </div>
    </div>
</x-app-layout>
