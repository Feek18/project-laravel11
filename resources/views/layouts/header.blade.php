<nav class="bg-white border-gray-200 dark:bg-gray-900 z-20 relative">
    <div class="font-poppins max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white ">KelasReady</span>
        </a>
        <button data-collapse-toggle="navbar-hamburger" type="button"
            class="lg:hidden inline-flex items-center justify-center p-2 w-10 h-10 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-hamburger" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="lg:flex lg:items-center lg:gap-12 hidden w-full md:block md:w-auto" id="navbar-hamburger">
            <ul
                class="text-sm font-medium flex flex-col py-4 md:p-0 mt-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0">
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent">Beranda</a>
                </li>
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent">Ruangan</a>
                </li>
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent">Tentang</a>
                </li>
                <li>
                    <a href="#"
                        class="block py-2 px-3 text-white rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 md:dark:hover:bg-transparent">Kontak</a>
                </li>
            </ul>
            <div>
                @guest
                    <a href="{{ route('login') }}">
                        <button type="button"
                            class="text-white text-sm bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-md px-8 py-2 me-2 focus:outline-none">Masuk</button>
                    </a>
                @endguest
                @auth
                    <button class="flex items-center gap-3 text-white text-sm" id="avatarButton" type="button"
                        data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start">
                        <img class="w-10 h-10 rounded-full cursor-pointer bg-cover bg-center"
                            src="{{ asset('storage/' . auth()->user()->pengguna->gambar) }}" alt="User dropdown">
                        <div class="hidden md:block text-left">
                            <h3>{{ Auth::user()->pengguna->nama }}</h3>
                            <p class="text-xs font-normal opacity-75">{{ Auth::user()->email }} </p>
                        </div>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="userDropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            <h3>Signed as</h3>
                            <p class="font-medium truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="avatarButton">
                            @role('pengguna')
                                <li>
                                    <a href="{{ route('users.index') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                                </li>
                            @endrole
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
                @endauth
            </div>
        </div>
    </div>
</nav>
