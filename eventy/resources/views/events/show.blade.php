<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-bold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $event->name }}
            </h2>
           
               
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            @if($event->image)
                            <div class="mb-6">
                                <img src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->name }}" class="w-full h-auto rounded-lg shadow-md mb-4 object-cover max-h-96">
                            </div>
                            @endif
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">{{ __('Description') }}</h3>
                                <p class="whitespace-pre-line">{{ $event->description }}</p>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">{{ __('Tags') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($event->tags as $tag)
                                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-sm">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Replace your existing like button section with this enhanced version -->

<!-- Like Button Section -->
<div class="mb-6 flex items-center space-x-2" id="like-section">
    @auth
        @php
            $hasLiked = $event->likes()->where('user_id', Auth::id())->exists();
        @endphp
        <button id="like-button" 
            class="inline-flex items-center px-3 py-1 border rounded-md text-sm font-medium transition-all duration-200 ease-in-out transform hover:scale-105 {{ $hasLiked ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 border-red-300 dark:border-red-700' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600' }}"
            data-event-id="{{ $event->id }}"
            data-liked="{{ $hasLiked ? 'true' : 'false' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 {{ $hasLiked ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span id="like-text">{{ $hasLiked ? 'Liked' : 'Like' }}</span>
        </button>
    @else
        <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 transition-all duration-200 ease-in-out transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            Like
        </a>
    @endauth
    <span class="text-sm text-gray-600 dark:text-gray-400" id="likes-count">
        {{ $event->likes()->count() }} {{ Str::plural('like', $event->likes()->count()) }}
    </span>
</div>


<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="notification-container" class="fixed bottom-4 right-4 z-50 flex flex-col space-y-2"></div>

                            <!-- Comments Section -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">{{ __('Comments') }} ({{ $event->comments()->count() }})</h3>
                                
                                @auth
                                    <div class="mb-4">
                                        <form id="comment-form" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <textarea name="content" id="comment-content" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Leave a comment..."></textarea>
                                            <div class="flex justify-end">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    {{ __('Post Comment') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                                        <p class="text-gray-600 dark:text-gray-400">{{ __('Please') }} <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('log in') }}</a> {{ __('to leave a comment.') }}</p>
                                    </div>
                                @endauth
                                
                                <div id="comments-container" class="space-y-4">
                                    @foreach ($event->comments()->with('user')->latest()->get() as $comment)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg comment-item" id="comment-{{ $comment->id }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center space-x-2">
                                                <div class="font-medium">{{ $comment->user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                                            </div>
                                            
                                            @auth
                                                @if (Auth::id() === $comment->user_id || Auth::id() === $event->user_id)
                                                <div class="flex items-center space-x-2">
                                                    @if (Auth::id() === $comment->user_id)
                                                    <button class="edit-comment-btn text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300" data-comment-id="{{ $comment->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    @endif
                                                    <button class="delete-comment-btn text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" data-comment-id="{{ $comment->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @endif
                                            @endauth
                                        </div>
                                        
                                        <div class="mt-2 comment-content">{{ $comment->content }}</div>
                                        
                                        <div class="mt-2 hidden edit-comment-form" id="edit-form-{{ $comment->id }}">
                                            <form class="space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <textarea name="content" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $comment->content }}</textarea>
                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" class="cancel-edit inline-flex items-center px-3 py-1 bg-gray-200 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-500">
                                                        {{ __('Cancel') }}
                                                    </button>
                                                    <button type="submit" class="save-edit inline-flex items-center px-3 py-1 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md text-xs text-white hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                                        {{ __('Save') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Additional event details can be added here -->
                            
                                  <!-- filepath: d:\eventy-1\eventy\resources\views\events\show.blade.php -->
                                            @auth
    @php
        $alreadySaved = $event->savedEvents()->where('user_id', Auth::id())->exists();
    @endphp
    <button 
        id="attend-button"
        data-event-id="{{ $event->id }}"
        class="inline-flex items-center px-4 py-2 {{ $alreadySaved ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700' }} dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        {{ $alreadySaved ? 'disabled' : '' }}
    >
        {{ $alreadySaved ? 'Saved to My Events' : 'Attend / Save to My Events' }}
    </button>
@else
    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        Login to Save Event
    </a>
@endauth

<script>
document.addEventListener('DOMContentLoaded', function () {
    const attendButton = document.getElementById('attend-button');
    if (attendButton) {
        attendButton.addEventListener('click', async function () {
            const eventId = attendButton.dataset.eventId;
            attendButton.disabled = true;
            const originalText = attendButton.textContent;
            attendButton.textContent = 'Saving...';

            try {
                const response = await fetch('{{ route('saved-events.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ event_id: eventId }),
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error('Network response was not ok');
                await response.json();

                attendButton.textContent = 'Saved to My Events';
                attendButton.classList.add('bg-gray-400');
                attendButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                showNotification('Event saved to your My Events.', 'success');
            } catch (error) {
                attendButton.textContent = originalText;
                showNotification('Could not save event. Please try again.', 'error');
            }
        });
    }

    function showNotification(message, type = 'success') {
        let notificationContainer = document.getElementById('notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
            document.body.appendChild(notificationContainer);
        }
        const notification = document.createElement('div');
        notification.className = `transform transition-all duration-300 ease-out px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 max-w-xs ${
            type === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
            'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
        }`;
        notification.innerHTML = `<div>${message}</div>`;
        notificationContainer.appendChild(notification);
        setTimeout(() => notification.remove(), 2500);
    }
});
</script>
                          
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 



<script>



                                    document.addEventListener('DOMContentLoaded', function () {
                                        const attendButton = document.getElementById('attend-button');
                                        const attendButtonText = document.getElementById('attend-button-text');

                                        if (attendButton) {
                                            attendButton.addEventListener('click', async function () {
                                                const eventId = {{ $event->id }};
                                                const isAttending = attendButtonText.textContent.trim() === 'You are attending';

                                                // Disable button during request
                                                attendButton.disabled = true;

                                                // Add loading state
                                                const originalText = attendButtonText.textContent;
                                                attendButtonText.textContent = 'Processing...';

                                                try {
                                                    // Send AJAX request
                                                    const response = await fetch(`/events/${eventId}/attendance`, {
                                                        method: isAttending ? 'DELETE' : 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                            'Accept': 'application/json',
                                                        },
                                                        credentials: 'same-origin',
                                                    });

                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok');
                                                    }

                                                    const data = await response.json();

                                                    // Update button text and appearance
                                                    attendButtonText.textContent = isAttending ? 'Attend Event' : 'You are attending';

                                                    // Show success notification
                                                    showNotification(isAttending ? 'You have canceled your attendance.' : 'You are now attending this event.', 'success');
                                                } catch (error) {
                                                    console.error('Error:', error);
                                                    showNotification('Something went wrong. Please try again.', 'error');
                                                } finally {
                                                    // Restore button state
                                                    attendButton.disabled = false;
                                                    attendButtonText.textContent = originalText;
                                                }
                                            });
                                        }
                                    });

                                    // Notification system
                                    function showNotification(message, type = 'success') {
                                        let notificationContainer = document.getElementById('notification-container');
                                        if (!notificationContainer) {
                                            notificationContainer = document.createElement('div');
                                            notificationContainer.id = 'notification-container';
                                            notificationContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
                                            document.body.appendChild(notificationContainer);
                                        }

                                        const notification = document.createElement('div');
                                        notification.className = `transform transition-all duration-300 ease-out translate-y-2 opacity-0 
                                            px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 max-w-xs 
                                            ${type === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
                                              'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'}`;

                                        const icon = type === 'success' 
                                            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                               </svg>`
                                            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                               </svg>`;

                                        notification.innerHTML = `
                                            <div class="flex-shrink-0">${icon}</div>
                                            <div>${message}</div>
                                        `;

                                        notificationContainer.appendChild(notification);

                                        setTimeout(() => {
                                            notification.classList.remove('translate-y-2', 'opacity-0');
                                            notification.classList.add('translate-y-0', 'opacity-100');
                                        }, 10);

                                        setTimeout(() => {
                                            notification.classList.add('translate-y-2', 'opacity-0');
                                            setTimeout(() => {
                                                notification.remove();
                                            }, 300);
                                        }, 3000);
                                    }
                             


    
    

       
    // Notification system
    function showNotification(message, type = 'success') {
        // Check if notification container exists, create if not
        let notificationContainer = document.getElementById('notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
            document.body.appendChild(notificationContainer);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `transform transition-all duration-300 ease-out translate-y-2 opacity-0 
            px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 max-w-xs 
            ${type === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 
              'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'}`;

        const icon = type === 'success' 
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
               </svg>`;

        notification.innerHTML = `
            <div class="flex-shrink-0">${icon}</div>
            <div>${message}</div>
        `;

        // Add to container
        notificationContainer.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-y-2', 'opacity-0');
            notification.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        // Remove after delay
        setTimeout(() => {
            notification.classList.add('translate-y-2', 'opacity-0');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

document.addEventListener('DOMContentLoaded', function () {
    const attendButton = document.getElementById('attend-button');
    if (attendButton) {
        attendButton.addEventListener('click', async function () {
            const eventId = attendButton.dataset.eventId;
            attendButton.disabled = true;
            const originalText = attendButton.textContent;
            attendButton.textContent = 'Saving...';

            try {
                const response = await fetch('{{ route('saved-events.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ event_id: eventId }),
                    credentials: 'same-origin',
                });

                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();

                attendButton.textContent = 'Saved!';
                showNotification('Event saved to your saved events.', 'success');
            } catch (error) {
                attendButton.textContent = originalText;
                showNotification('Could not save event. Please try again.', 'error');
            } finally {
                setTimeout(() => {
                    attendButton.disabled = false;
                    attendButton.textContent = originalText;
                }, 1500);
            }
        });
    }
});
</script>