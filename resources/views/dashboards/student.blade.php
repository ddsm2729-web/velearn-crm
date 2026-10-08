<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Dashboard') }}
            </h2>
            <span class="px-3 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full dark:bg-emerald-900 dark:text-emerald-200">
                Student
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Enrolled Courses</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">3</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Overall Progress</div>
                    <div class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">75%</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Tasks</div>
                    <div class="mt-2 text-3xl font-bold text-amber-600 dark:text-amber-400">2</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Score</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">96%</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">My Learning Journey</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Access your enrolled courses, view upcoming live classes, submit assignments, and track certificate progress.</p>
            </div>
        </div>
    </div>
</x-app-layout>
