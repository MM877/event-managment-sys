<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Image Gallery') }}
            </h2>
            <x-button-link href="{{ route('galleries.create') }}">
                {{ __('Add Gallery Image') }}
            </x-button-link>
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
                    <h3 class="text-lg font-medium mb-4">{{ __('Event Gallery') }}</h3>
                    
                    @if($galleries->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($galleries as $gallery)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                                    <div class="h-36 overflow-hidden">
                                        @if($gallery->image)
                                            <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->caption }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="h-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                                <span class="text-gray-500 dark:text-gray-400">{{ __('No Image') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2 font-medium line-clamp-2">{{ $gallery->caption }}</p>
                                        
                                        <div class="flex items-center text-xs text-gray-500 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $gallery->created_at->format('M j, Y') }}</span>
                                        </div>
                                        
                                        <div class="flex items-center text-xs text-gray-500 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2z" />
                                            </svg>
                                            <span>{{ __('Event:') }} {{ $gallery->event->name }}</span>
                                        </div>
                                        
                                        <div class="mt-3 flex justify-between items-center">
                                            <a href="{{ route('galleries.show', $gallery) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                                {{ __('View') }}
                                            </a>
                                            
                                            @auth
                                                @if(Auth::id() === $gallery->user_id)
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('galleries.edit', $gallery) }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form method="POST" action="{{ route('galleries.destroy', $gallery) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $galleries->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-4 text-lg">{{ __("No gallery images have been added yet.") }}</p>
                            @auth
                            <a href="{{ route('galleries.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-400 focus:bg-indigo-700 dark:focus:bg-indigo-400 active:bg-indigo-900 dark:active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Add the First Image') }}
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-400 focus:bg-indigo-700 dark:focus:bg-indigo-400 active:bg-indigo-900 dark:active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Sign In to Add Images') }}
                            </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
