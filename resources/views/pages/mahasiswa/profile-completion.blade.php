<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - {{ env('APP_NAME') }}</title>
    <link rel="icon" href="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.svg') }}" type="image/svg+xml" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <img src="{{ asset('assets/image/Lambang_UIN_Ar-Raniry.png') }}" alt="UIN Ar-Raniry" class="h-20 w-auto">
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Lengkapi Profil Anda
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Halo <span class="font-semibold">{{ $user->name }}</span>, silakan lengkapi data berikut sebelum masuk ke dashboard.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
            
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('mahasiswa.profile.complete.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Grid Layout untuk Form -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- NIM -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="nim" class="block text-sm font-medium text-gray-700">
                            NIM (Nomor Induk Mahasiswa) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="nim" name="nim" type="text" required value="{{ old('nim') }}"
                                class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="Misal: 200101001">
                        </div>
                        @error('nim')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Angkatan -->
                    <div>
                        <label for="angkatan" class="block text-sm font-medium text-gray-700">
                            Tahun Angkatan <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="angkatan" name="angkatan" required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white transition duration-150 ease-in-out">
                                <option value="" disabled selected>Pilih Angkatan</option>
                                @php $currentYear = date('Y'); @endphp
                                @for ($year = $currentYear; $year >= $currentYear - 7; $year--)
                                    <option value="{{ $year }}" {{ old('angkatan') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        @error('angkatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>



                    <!-- Nomor HP / WhatsApp -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                +62
                            </span>
                            <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}"
                                class="flex-1 min-w-0 block w-full px-3 py-2.5 rounded-none rounded-r-lg border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" 
                                placeholder="81234567890">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Gunakan format tanpa angka 0 di depan (contoh: 812...)</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Profil -->
                    <div class="col-span-1 md:col-span-2 border-t border-gray-100 pt-5 mt-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Foto Profil (Opsional)
                        </label>
                        <div class="mt-2 flex items-center">
                            <span class="inline-block h-16 w-16 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                <svg id="default-avatar-icon" class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <img id="preview-image" class="h-full w-full object-cover hidden" src="#" alt="Preview Foto">
                            </span>
                            <div class="ml-5">
                                <input type="file" name="photo" id="photo" accept="image/*" class="sr-only" onchange="updateFileName(this)">
                                <label for="photo" class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    Pilih Gambar
                                </label>
                                <p id="file-name" class="mt-2 text-xs text-gray-500">JPG, JPEG, PNG hingga 2MB</p>
                            </div>
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Simpan dan Masuk Dashboard
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition duration-150 ease-in-out">
                    Atau Logout dan lanjutkan nanti
                </button>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('file-name');
            const previewImage = document.getElementById('preview-image');
            const defaultIcon = document.getElementById('default-avatar-icon');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Update file name display
                fileNameDisplay.textContent = file.name;
                fileNameDisplay.classList.add('text-blue-600');

                // Image preview using FileReader
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    defaultIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);

            } else {
                // Reset if no file is selected
                fileNameDisplay.textContent = 'JPG, JPEG, PNG hingga 2MB';
                fileNameDisplay.classList.remove('text-blue-600');
                previewImage.src = '#';
                previewImage.classList.add('hidden');
                defaultIcon.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
