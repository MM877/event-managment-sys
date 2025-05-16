<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Events I\'m Attending') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __("Your Upcoming Events") }}</h3>
                    
                    @if(isset($events) && count($events) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($events as $event)
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                    @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="h-48 w-full object-cover">
                                    @else
                                        <div class="h-48 bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-gray-500 dark:text-gray-400">No image</span>
                                        </div>
                                    @endif
                                    
                                    <div class="p-4">
                                        <h4 class="text-lg font-bold mb-2">{{ $event->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                            <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full px-3 py-1 text-xs mr-2">
                                                {{ $event->start_date ? date('M d, Y', strtotime($event->start_date)) : 'Date TBD' }}
                                            </span>
                                            @if($event->country)
                                            <span class="inline-block bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full px-3 py-1 text-xs">
                                                {{ $event->country->name }}
                                            </span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                                            {{ $event->description }}
                                        </p>
                                        
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                                View Event
                                            </a>
                                            
                                            <form action="{{ route('attending.destroy', $event->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                                    Cancel Attendance
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">You're not attending any events</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Find events you're interested in and sign up to attend.</p>
                            <div class="mt-6">
                                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Browse Events
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 