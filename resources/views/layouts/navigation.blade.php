<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-gray-900">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('jadwal.index')" :active="request()->routeIs('jadwal.*')">
                        {{ __('Jadwal') }}
                    </x-nav-link>
                    <x-nav-link :href="route('matkul.index')" :active="request()->routeIs('matkul.*')">
                        {{ __('Mata Kuliah') }}
                    </x-nav-link>
                    <x-nav-link :href="route('ruangan.index')" :active="request()->routeIs('ruangan.*')">
                        {{ __('Ruangan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('akun.index')" :active="request()->routeIs('akun.index')">
                        {{ __('Akun') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pengguna.index')" :active="request()->routeIs('pengguna.index')">
                        {{ __('Pengguna') }}
                    </x-nav-link>
                    <x-nav-link :href="route('peminjam')" :active="request()->routeIs('peminjam')">
                        {{ __('Peminjam') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <button class="flex items-center gap-3 text-white text-sm" id="avatarButton" type="button"
                    data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start">
                    <img class="w-10 h-10 rounded-full cursor-pointer bg-cover bg-center"
                        src="https://i.pinimg.com/736x/0f/68/94/0f6894e539589a50809e45833c8bb6c4.jpg"
                        alt="User dropdown">
                    <div class="hidden md:block text-left">
                        <p>Signed as</p>
                        <p class="text-xs font-normal opacity-75">{{ Auth::user()->email }} </p>
                    </div>
                </button>
                <!-- Dropdown menu -->
                <div id="userDropdown"
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                    {{-- <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        <div>Bonnie Green</div>
                        <div class="font-medium truncate">name@flowbite.com</div>
                    </div> --}}
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                        </li>
                    </ul>
                    <div class="py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                class="text-red-500 font-medium">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('jadwal.index')" :active="request()->routeIs('jadwal.*')">
                {{ __('Jadwal') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ruangan.index')" :active="request()->routeIs('ruangan.*')">
                {{ __('Ruangan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pengguna.index')" :active="request()->routeIs('pengguna.index')">
                {{ __('Pengguna') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peminjam')" :active="request()->routeIs('peminjam')">
                {{ __('Peminjam') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

</nav>
