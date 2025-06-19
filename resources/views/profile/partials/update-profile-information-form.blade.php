<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form> --}}

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Jika ingin aktifkan upload gambar, buka komentar ini --}}

        <div>
            <x-input-label for="gambar" :value="__('Pilih Gambar')" />

            <input id="gambar" name="gambar" type="file" accept="image/*"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:text-blue-700
               focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('gambar', optional($user->pengguna)->gambar) }}" onchange="previewImage(event)" />

            <x-input-error class="mt-2" :messages="$errors->get('gambar')" />

            <!-- Gambar Preview -->
            <div class="mt-4">
                <img id="preview" src="#" alt="Preview Gambar"
                    class="hidden w-64 h-40 object-cover rounded-lg shadow" />
            </div>
        </div>

        <div>
            <x-input-label for="nama" :value="__('Nama')" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', optional($user->pengguna)->nama)"
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="no_telp" :value="__('No. Telp')" />
            <x-text-input id="no_telp" name="no_telp" type="tel" class="mt-1 block w-full" :value="old('no_telp', optional($user->pengguna)->no_telp)"
                autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('no_telp')" />
        </div>

        <div>
            <x-input-label for="alamat" :value="__('Alamat')" />
            <textarea id="alamat" name="alamat"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                autofocus autocomplete="street-address">{{ old('alamat', optional($user->pengguna)->alamat) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
            <select id="gender" name="gender"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                autocomplete="sex">
                <option value="pria"
                    {{ old('gender', optional($user->pengguna)->gender) == 'pria' ? 'selected' : '' }}>Pria</option>
                <option value="wanita"
                    {{ old('gender', optional($user->pengguna)->gender) == 'wanita' ? 'selected' : '' }}>Wanita</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<!-- Script untuk preview -->
@push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
