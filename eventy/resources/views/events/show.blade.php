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
                            
                            @if($event->num_tickets)
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3">{{ __('Tickets') }}</h3>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ __('Available Tickets:') }} 
                                            <span class="font-semibold {{ $event->remainingTickets > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $event->remainingTickets }}
                                            </span>
                                        </p>
                                        @if($event->tickets()->where('user_id', Auth::id())->where('status', 'booked')->exists())
                                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                                {{ __('You have') }} 
                                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                                    {{ $event->tickets()->where('user_id', Auth::id())->where('status', 'booked')->sum('quantity') }}
                                                </span>
                                                {{ __('tickets booked for this event.') }}
                                            </p>
                                        @endif
                                    </div>
                                    @auth
                                        @if($event->remainingTickets > 0 && $event->user_id !== Auth::id() && $event->end_date > now())
                                            <form id="book-ticket-form" class="flex items-center space-x-2">
                                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                <div>
                                                    <label for="quantity" class="sr-only">{{ __('Quantity') }}</label>
                                                    <select id="ticket-quantity" name="quantity" class="text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        @for($i = 1; $i <= min(10, $event->remainingTickets); $i++)
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <button type="submit" id="book-ticket-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    {{ __('Book') }}
                                                </button>
                                            </form>
                                        @elseif($event->user_id === Auth::id())
                                            <p class="text-gray-500 dark:text-gray-400 italic">{{ __('You are the event creator') }}</p>
                                        @elseif($event->end_date <= now())
                                            <p class="text-red-500 dark:text-red-400">{{ __('Event has ended') }}</p>
                                        @else
                                            <p class="text-red-500 dark:text-red-400">{{ __('Sold out') }}</p>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            {{ __('Login to Book Tickets') }}
                                        </a>
                                    @endauth
                                </div>
                            </div>
                            @endif
                            
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
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
// Ticket booking functionality with integrated attendance
document.addEventListener('DOMContentLoaded', function() {
    const bookTicketForm = document.getElementById('book-ticket-form');
    if (bookTicketForm) {
        bookTicketForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const eventId = bookTicketForm.querySelector('input[name="event_id"]').value;
            const quantity = document.getElementById('ticket-quantity').value;
            const bookTicketButton = document.getElementById('book-ticket-button');
            
            // Disable the button and change text
            bookTicketButton.disabled = true;
            const originalText = bookTicketButton.textContent;
            bookTicketButton.textContent = 'Processing...';
            
            try {
                // Book tickets and mark attendance in one request
                const response = await fetch('/tickets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        quantity: quantity
                    }),
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    showNotification('Success! Tickets booked and event saved.', 'success');
                    
                    // Update the page to reflect the new ticket count
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to book tickets');
                }
            } catch (error) {
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
                bookTicketButton.disabled = false;
                bookTicketButton.textContent = originalText;
            }
        });
    }
    
    // Like button functionality
    const likeButton = document.getElementById('like-button');
    if (likeButton) {
        likeButton.addEventListener('click', async function() {
            const eventId = likeButton.dataset.eventId;
            const isLiked = likeButton.dataset.liked === 'true';
            const likeText = document.getElementById('like-text');
            const likesCount = document.getElementById('likes-count');
            
            try {
                const url = isLiked ? '/likes/destroy' : '/likes';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ event_id: eventId }),
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Toggle liked state
                    likeButton.dataset.liked = isLiked ? 'false' : 'true';
                    
                    // Update button appearance
                    if (isLiked) {
                        likeButton.classList.remove('bg-red-100', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-300', 'border-red-300', 'dark:border-red-700');
                        likeButton.classList.add('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-600');
                        likeText.textContent = 'Like';
                        likeButton.querySelector('svg').setAttribute('fill', 'none');
                        likeButton.querySelector('svg').classList.remove('text-red-500', 'dark:text-red-400');
                        likeButton.querySelector('svg').classList.add('text-gray-500', 'dark:text-gray-400');
                    } else {
                        likeButton.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300', 'border-gray-300', 'dark:border-gray-600');
                        likeButton.classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-300', 'border-red-300', 'dark:border-red-700');
                        likeText.textContent = 'Liked';
                        likeButton.querySelector('svg').setAttribute('fill', 'currentColor');
                        likeButton.querySelector('svg').classList.remove('text-gray-500', 'dark:text-gray-400');
                        likeButton.querySelector('svg').classList.add('text-red-500', 'dark:text-red-400');
                    }
                    
                    // Update likes count
                    likesCount.textContent = `${data.likes_count} ${data.likes_count === 1 ? 'like' : 'likes'}`;
                }
            } catch (error) {
                showNotification('Could not update like status. Please try again.', 'error');
            }
        });
    }
    
    // Comment form submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const eventId = commentForm.querySelector('input[name="event_id"]').value;
            const content = document.getElementById('comment-content').value;
            const submitButton = commentForm.querySelector('button[type="submit"]');
            
            if (!content.trim()) {
                showNotification('Please enter a comment.', 'error');
                return;
            }
            
            // Disable button and change text
            submitButton.disabled = true;
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Posting...';
            
            try {
                const response = await fetch('/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        content: content
                    }),
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Clear the form
                    document.getElementById('comment-content').value = '';
                    
                    // Reload page to show the new comment with proper formatting
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Failed to post comment');
                }
            } catch (error) {
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    }
    
    // Edit & Delete comment buttons
    document.querySelectorAll('.edit-comment-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const commentItem = document.getElementById(`comment-${commentId}`);
            const editForm = document.getElementById(`edit-form-${commentId}`);
            
            // Toggle the form display
            commentItem.querySelector('.comment-content').classList.toggle('hidden');
            editForm.classList.toggle('hidden');
        });
    });
    
    document.querySelectorAll('.cancel-edit').forEach(button => {
        button.addEventListener('click', function() {
            const commentItem = this.closest('.comment-item');
            const commentContent = commentItem.querySelector('.comment-content');
            const editForm = commentItem.querySelector('.edit-comment-form');
            
            commentContent.classList.remove('hidden');
            editForm.classList.add('hidden');
        });
    });
    
    document.querySelectorAll('.edit-comment-form form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const commentItem = this.closest('.comment-item');
            const commentId = commentItem.id.replace('comment-', '');
            const content = this.querySelector('textarea').value;
            const saveButton = this.querySelector('.save-edit');
            
            if (!content.trim()) {
                showNotification('Comment cannot be empty.', 'error');
                return;
            }
            
            saveButton.disabled = true;
            const originalText = saveButton.textContent;
            saveButton.textContent = 'Saving...';
            
            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content: content }),
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Update the comment content in the DOM
                    commentItem.querySelector('.comment-content').textContent = content;
                    
                    // Hide the edit form
                    commentItem.querySelector('.comment-content').classList.remove('hidden');
                    commentItem.querySelector('.edit-comment-form').classList.add('hidden');
                    
                    showNotification('Comment updated successfully.', 'success');
                } else {
                    throw new Error(data.message || 'Failed to update comment');
                }
            } catch (error) {
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = originalText;
            }
        });
    });
    
    document.querySelectorAll('.delete-comment-btn').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            
            const commentId = this.dataset.commentId;
            const commentItem = document.getElementById(`comment-${commentId}`);
            
            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Remove the comment from the DOM
                    commentItem.remove();
                    showNotification('Comment deleted successfully.', 'success');
                } else {
                    throw new Error(data.message || 'Failed to delete comment');
                }
            } catch (error) {
                showNotification(error.message || 'An error occurred. Please try again.', 'error');
            }
        });
    });
    
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
            type === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' :
            'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
        }`;
        notification.innerHTML = `<div>${message}</div>`;
        notificationContainer.appendChild(notification);
        setTimeout(() => notification.remove(), 2500);
    }
});
</script> 