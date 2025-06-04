<x-user.layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-8">
            <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-4">
                <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg p-4 my-6" role="alert">
                    <p class="font-medium">Informasi:</p>
                    <p>Silakan lengkapi biodata Anda terlebih dahulu sebelum mengajukan peminjaman ruang kuliah.</p>
                </div>
                <h2 class="text-2xl font-medium text-gray-900">Profile Management</h2>
            </div>
            <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-4 py-6">
                <div class="flex flex-col lg:flex-row lg:space-x-6 space-y-6 lg:space-y-0">

                    <!-- Update Profile Information -->
                    <div class="w-full lg:w-1/2 p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl mx-auto">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div class="w-full lg:w-1/2 p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl mx-auto">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="py-12">


    </div>
</x-user.layouts.app>
