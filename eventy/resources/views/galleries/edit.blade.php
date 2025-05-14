<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-4">
            <h2 class="font-bold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Gallery Image') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('Update gallery image details') }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('galleries.update', $gallery) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Event Selection Dropdown -->
                        <div>
                            <x-input-label for="event_id" :value="__('Event')" />
                            <select id="event_id" name="event_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select an event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ (old('event_id', $gallery->event_id) == $event->id) ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                        </div>
                        
                        <!-- Image Caption Field -->
                        <div>
                            <x-input-label for="caption" :value="__('Image Caption')" />
                            <x-text-input id="caption" class="block mt-1 w-full" type="text" name="caption" :value="old('caption', $gallery->caption)" required autofocus />
                            <x-input-error :messages="$errors->get('caption')" class="mt-2" />
                        </div>
                        
                        <!-- Image Upload -->
                        <div>
                            <x-input-label for="image" :value="__('Gallery Image')" />
                            
                            @if($gallery->image)
                                <div class="mt-2 mb-4">
                                    <p class="text-sm text-gray-500 mb-2">{{ __('Current Image:') }}</p>
                                    <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->caption }}" class="max-h-48 max-w-xs rounded-lg shadow-md">
                                </div>
                            @endif
                            
                            <input id="image" name="image" type="file" accept="image/*" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Upload a new image (JPEG, PNG, JPG, GIF - max 2MB)') }}
                            </p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-2 hidden">
                                <p class="text-sm text-gray-500 mb-2">{{ __('New Image Preview:') }}</p>
                                <img id="previewImage" class="max-h-48 max-w-xs rounded-lg shadow-md" alt="Image preview" />
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('galleries.show', $gallery) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancel') }}
                            </a>
                            
                            <x-primary-button>
                                {{ __('Update Image') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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