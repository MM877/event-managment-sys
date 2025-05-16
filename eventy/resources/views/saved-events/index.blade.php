<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Saved Events') }}
       </h2>
   </x-slot>

   <div class="py-12">
       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-6 text-gray-900 dark:text-gray-100">
                   <h3 class="text-lg font-medium mb-4">{{ __("Your Saved Events") }}</h3>
                   
                   @if(isset($savedEvents) && count($savedEvents) > 0)
                       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                           @foreach($savedEvents as $savedEvent)
                               <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                   @if($savedEvent->event->image)
                                       <img src="{{ asset('storage/' . $savedEvent->event->image) }}" alt="{{ $savedEvent->event->title }}" class="h-48 w-full object-cover">
                                   @else
                                       <div class="h-48 bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                           <span class="text-gray-500 dark:text-gray-400">No image</span>
                                       </div>
                                   @endif
                                   
                                   <div class="p-4">
                                       <h4 class="text-lg font-bold mb-2">{{ $savedEvent->event->title }}</h4>
                                       <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                           <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full px-3 py-1 text-xs mr-2">
                                               {{ date('M d, Y', strtotime($savedEvent->event->date)) }}
                                           </span>
                                           <span class="inline-block bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-full px-3 py-1 text-xs">
                                               {{ $savedEvent->event->country->name }}
                                           </span>
                                       </p>
                                       <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                                           {{ $savedEvent->event->description }}
                                       </p>
                                       
                                       <div class="flex justify-between items-center">
                                           <a href="{{ route('events.show', $savedEvent->event->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                               View Event
                                           </a>
                                           
                                           <form method="POST" action="{{ route('saved-events.destroy', $savedEvent->id) }}">
                                               @csrf
                                               @method('DELETE')
                                               <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                                   Remove
                                               </button>
                                           </form>
                                       </div>
                                   </div>
                               </div>
                           @endforeach
                       </div>
                   @else
                       <div class="text-center py-12">
                           <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                               <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                           </svg>
                           <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No saved events</h3>
                           <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bookmark events you're interested in to find them easily later.</p>
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
