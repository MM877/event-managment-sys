<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-4">
            <h2 class="font-bold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Event') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Fill in the details below to create your event') }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('events.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Event Name Field -->
                        <div>
                            <x-input-label for="name" :value="__('Event Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Enter Event Name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Event Description Field -->
                        <div>
                            <x-input-label for="description" :value="__('Event Description')" />
                            <textarea id="description" name="description" rows="5" maxlength="500" placeholder="Provide a detailed description of the event" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <div class="flex justify-between mt-1">
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                <span class="text-xs text-gray-500" id="char-count">0/500 characters</span>
                            </div>
                        </div>
                        
                        <!-- Event Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date')" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date')" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Event Image Upload -->
                        <div>
                            <x-input-label for="image" :value="__('Event Image')" />
                            <input id="image" name="image" type="file" accept="image/*" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Upload an image for your event (JPEG, PNG, JPG, GIF - max 2MB)') }}
                            </p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-2 hidden">
                                <img id="previewImage" class="max-w-xs rounded-lg shadow-md" alt="Image preview" />
                            </div>
                        </div>
                        
                        <!-- Event Location Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Country Selection Dropdown -->
                            <div>
                                <x-input-label for="country_id" :value="__('Country')" />
                                <select id="country_id" name="country_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select a country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                            </div>
                            
                            <!-- City Input Field -->
                            <div>
                                <x-input-label for="city" :value="__('City')" />
                                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required placeholder="Enter City Name" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Ticket Quantity Field -->
                        <div>
                            <x-input-label for="num_tickets" :value="__('Number of Available Tickets')" />
                            <x-text-input id="num_tickets" class="block mt-1 w-full" type="number" name="num_tickets" :value="old('num_tickets')" min="0" placeholder="Enter the number of available tickets" />
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Enter the total number of tickets available for this event.') }}
                            </p>
                            <x-input-error :messages="$errors->get('num_tickets')" class="mt-2" />
                        </div>
                        
                        <!-- Tags Selection Field -->
                        <div>
                            <x-input-label for="tags" :value="__('Tags')" />
                            <div class="mt-1">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach($tags as $tag)
                                        <div class="flex items-center">
                                            <input id="tag-{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                                {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                            <label for="tag-{{ $tag->id }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                                {{ $tag->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ __('Select relevant tags to make your event more discoverable') }}
                                </p>
                                <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Create Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Character Counter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Character counter for description
            const descriptionField = document.getElementById('description');
            const charCount = document.getElementById('char-count');
            
            // Initial count
            charCount.textContent = `${descriptionField.value.length}/500 characters`;
            
            // Update count on input
            descriptionField.addEventListener('input', function() {
                charCount.textContent = `${this.value.length}/500 characters`;
                
                // Visual feedback when approaching limit
                if (this.value.length > 450) {
                    charCount.classList.add('text-amber-500');
                } else {
                    charCount.classList.remove('text-amber-500');
                }
                
                if (this.value.length >= 500) {
                    charCount.classList.add('text-red-500');
                    charCount.classList.remove('text-amber-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            });

            // Image preview
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
