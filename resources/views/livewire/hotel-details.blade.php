<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Hotel - TravelEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-slate-50 dark:bg-slate-900">
    <div class="min-h-screen p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Add New Hotel</h1>
                <p class="text-slate-500 dark:text-slate-400">Fill in the details to add a new hotel to the system</p>
            </div>

            <!-- Form Card -->
            <div class="p-6 bg-white shadow-sm dark:bg-slate-800 rounded-xl">
                <form id="hotelForm" class="space-y-6" enctype="multipart/form-data">
                    <!-- Hotel Name -->
                    <div>
                        <label for="name" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                            Hotel Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 bg-white border rounded-lg border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400 focus:border-transparent dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400"
                            placeholder="Enter hotel name">
                        <p class="hidden mt-1 text-sm text-red-500" id="nameError">Hotel name is required</p>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <textarea id="location" name="location" required rows="3"
                            class="w-full px-4 py-2 bg-white border rounded-lg border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400 focus:border-transparent dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400"
                            placeholder="Enter detailed hotel location"></textarea>
                        <p class="hidden mt-1 text-sm text-red-500" id="locationError">Location is required</p>
                    </div>

                    <!-- Amount per Day -->
                    <div>
                        <label for="amount" class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                            Amount per Day <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute -translate-y-1/2 left-3 top-1/2 text-slate-400">$</span>
                            <input type="number" id="amount" name="amount_per_day" required min="0"
                                step="0.01"
                                class="w-full py-2 pl-8 pr-4 bg-white border rounded-lg border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400 focus:border-transparent dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400"
                                placeholder="0.00">
                        </div>
                        <p class="hidden mt-1 text-sm text-red-500" id="amountError">Valid amount is required</p>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block mb-1 text-sm font-medium text-slate-700 dark:text-slate-300">
                            Hotel Image <span class="text-red-500">*</span>
                        </label>

                        <!-- Image Preview -->
                        <div class="mb-4">
                            <div id="imagePreview"
                                class="relative hidden w-full h-64 overflow-hidden border-2 border-dashed rounded-lg border-slate-300 dark:border-slate-600">
                                <img id="preview" src="#" alt="Preview" class="object-cover w-full h-full">
                                <button type="button" onclick="removeImage()"
                                    class="absolute p-2 text-white bg-red-500 rounded-full top-2 right-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Upload Input -->
                        <div id="uploadBox" class="relative">
                            <input type="file" id="image" name="image" accept="image/*" required class="hidden"
                                onchange="previewImage(this)">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer border-slate-300 dark:border-slate-600 hover:border-teal-500 dark:hover:border-teal-400">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="mb-4 text-4xl fas fa-cloud-upload-alt text-slate-400"></i>
                                    <p class="mb-2 text-sm text-slate-500 dark:text-slate-400">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        PNG, JPG or JPEG (MAX. 2MB)
                                    </p>
                                </div>
                            </label>
                        </div>
                        <p class="hidden mt-1 text-sm text-red-500" id="imageError">Image is required</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" onclick="window.history.back()"
                            class="px-4 py-2 border rounded-lg border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-teal-500 rounded-lg hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Create Hotel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBox = document.getElementById('uploadBox');
            const file = input.files[0];

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (PNG, JPG or JPEG)');
                    input.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should be less than 2MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadBox.classList.add('hidden');
                }

                reader.readAsDataURL(file);
            }
        }

        // Remove image preview
        function removeImage() {
            const input = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBox = document.getElementById('uploadBox');

            input.value = '';
            imagePreview.classList.add('hidden');
            uploadBox.classList.remove('hidden');
        }

        // Form validation
        const form = document.getElementById('hotelForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset errors
            document.querySelectorAll('.text-red-500').forEach(el => el.classList.add('hidden'));

            let hasError = false;

            // Validate name
            const name = document.getElementById('name');
            if (!name.value.trim()) {
                document.getElementById('nameError').classList.remove('hidden');
                hasError = true;
            }

            // Validate location
            const location = document.getElementById('location');
            if (!location.value.trim()) {
                document.getElementById('locationError').classList.remove('hidden');
                hasError = true;
            }

            // Validate amount
            const amount = document.getElementById('amount');
            if (!amount.value || amount.value <= 0) {
                document.getElementById('amountError').classList.remove('hidden');
                hasError = true;
            }

            // Validate image
            const image = document.getElementById('image');
            if (!image.files[0]) {
                document.getElementById('imageError').classList.remove('hidden');
                hasError = true;
            }

            if (!hasError) {
                // Here you would typically submit the form to your backend
                // For demonstration, we'll just log the form data
                const formData = new FormData(form);
                console.log('Form submitted:', Object.fromEntries(formData));

                // You can add your form submission logic here
                // Example:
                // fetch('/api/hotels', {
                //     method: 'POST',
                //     body: formData
                // })
            }
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('label[for="image"]');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-teal-500');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-teal-500');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('image');

            input.files = files;
            previewImage(input);
        }
    </script>
</body>

</html>
