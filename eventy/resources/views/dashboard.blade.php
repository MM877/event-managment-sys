<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __("Welcome to your Eventy dashboard!") }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- My Events -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <h4 class="font-bold text-lg mb-2">My Events</h4>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Manage events you've created</p>
                            <a href="/my-events" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View My Events
                            </a>
                        </div>
                        
                        <!-- Saved Events -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <h4 class="font-bold text-lg mb-2">Saved Events</h4>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Events you've bookmarked for later</p>
                            <a href="/saved-events" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Saved Events
                            </a>
                        </div>
                        
                        <!-- Galleries -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <h4 class="font-bold text-lg mb-2">Photo Galleries</h4>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Browse event photo galleries</p>
                            <a href="/galleries" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                View Galleries
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <a href="/events/create" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create New Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
