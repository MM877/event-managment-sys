<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gallery Image') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('galleries.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 dark:focus:bg-gray-600 active:bg-gray-900 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Back to Gallery') }}
                </a>
                @auth
                    @if(Auth::id() === $gallery->user_id)
                    <a href="{{ route('galleries.edit', $gallery) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Edit Image') }}
                    </a>
                    <form method="POST" action="{{ route('galleries.destroy', $gallery) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 dark:focus:bg-red-700 active:bg-red-900 dark:active:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Delete') }}
                        </button>
                    </form>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Image -->
                        <div class="flex justify-center items-start">
                            @if($gallery->image)
                                <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->caption }}" class="w-full max-w-md rounded-lg shadow-lg">
                            @else
                                <div class="h-64 w-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('No Image Available') }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex flex-col space-y-6">
                            <div>
                                <h3 class="text-2xl font-semibold mb-2">{{ $gallery->caption }}</h3>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Added on') }} {{ $gallery->created_at->format('F j, Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-md font-medium mb-4 text-gray-700 dark:text-gray-300">{{ __('Image Details') }}</h4>
                                
                                <div class="space-y-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('From Event') }}</div>
                                        <a href="{{ route('events.show', $gallery->event) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            {{ $gallery->event->name }}
                                        </a>
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Uploaded By') }}</div>
                                        <p>{{ $gallery->user->name }}</p>
                                    </div>
                                    
                                    @if($gallery->updated_at->gt($gallery->created_at))
                                    <div>
                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</div>
                                        <p>{{ $gallery->updated_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Related Images or other content could go here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 