<header class="fixed top-0 right-0 left-0 lg:left-64 z-30
               bg-white/80 backdrop-blur-xl
               border-b border-white/30
               shadow-[0_8px_30px_rgba(0,0,0,0.05)]">

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- LEFT SIDE -->
            <div class="flex items-center">

                <!-- Mobile Logo -->
                <div class="ml-3 lg:hidden flex items-center">
                    <div class="h-9 w-9 rounded-xl overflow-hidden mr-2 shadow-md">
                        <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg"
                             alt="Logo PSF"
                             class="h-full w-full object-cover"
                             onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold\'>P</div>';"/>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        PAYROLL PSF
                    </span>
                </div>

                <!-- Desktop Title -->
                <div class="hidden lg:block w-96">
                    <div class="relative group">

                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-indigo-600 transition">
                            <i class="fas fa-search text-sm"></i>
                        </span>

                        <input type="text"
                            placeholder="Search payroll, employee, reports..."
                            class="w-full pl-11 pr-4 py-2.5
                                    rounded-2xl
                                    bg-gray-100/70 backdrop-blur
                                    border border-transparent
                                    text-sm text-gray-700
                                    placeholder-gray-400
                                    focus:outline-none
                                    focus:ring-2 focus:ring-indigo-500
                                    focus:bg-white
                                    focus:border-indigo-200
                                    transition-all duration-300
                                    shadow-sm">

                        <!-- Subtle Glow Effect -->
                        <span class="absolute inset-0 rounded-2xl
                                    bg-gradient-to-r from-indigo-500 to-purple-500
                                    opacity-0 group-focus-within:opacity-10
                                    blur-xl transition duration-500 -z-10">
                        </span>

                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center">

                <div class="relative" x-data="{ open: false }">

                    <!-- TRIGGER -->
                    <button @click="open = !open"
                            class="group relative flex items-center space-x-3
                                   px-3 py-2 rounded-2xl
                                   transition-all duration-300
                                   hover:bg-white/60 hover:backdrop-blur
                                   hover:shadow-md hover:-translate-y-0.5">

                        <!-- Glow Aura -->
                        <span class="absolute inset-0 rounded-2xl
                                     bg-gradient-to-r from-indigo-500 to-purple-600
                                     opacity-0 group-hover:opacity-10
                                     blur-xl transition duration-500"></span>

                        <!-- Avatar -->
                        <div class="relative h-10 w-10 rounded-full overflow-hidden
                                    ring-2 ring-white shadow-lg
                                    transition duration-300
                                    group-hover:scale-105">

                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/profile-photos/' . auth()->user()->profile_photo) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gradient-to-br
                                            from-indigo-600 to-purple-600
                                            flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            <!-- Online Indicator -->
                            <span class="absolute bottom-0 right-0
                                         h-3 w-3 bg-emerald-500
                                         border-2 border-white rounded-full
                                         animate-pulse"></span>
                        </div>

                        <!-- Name -->
                        <div class="hidden md:block text-left leading-tight">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->role->name }}
                            </p>
                        </div>

                        <!-- Chevron -->
                        <i class="fas fa-chevron-down text-gray-400 text-xs
                                  transition-all duration-300 ease-out
                                  group-hover:text-indigo-600"
                           :class="{ 'rotate-180 text-indigo-600': open }"></i>
                    </button>

                    <!-- DROPDOWN -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-5 w-80
                                bg-white/85 backdrop-blur-2xl
                                rounded-3xl
                                shadow-[0_20px_60px_rgba(0,0,0,0.12)]
                                border border-white/30
                                overflow-hidden z-50">

                        <!-- Arrow Pointer -->
                        <div class="absolute -top-3 right-8 w-6 h-6
                                    bg-white/85 backdrop-blur-2xl
                                    rotate-45 border-l border-t border-white/30">
                        </div>

                        <!-- Profile Header -->
                        <div class="relative px-6 py-6
                                    bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600
                                    text-white overflow-hidden">

                            <!-- Glow Effect -->
                            <div class="absolute -top-10 -right-10 w-32 h-32
                                        bg-white/20 rounded-full blur-3xl"></div>

                            <div class="relative flex items-center space-x-4">

                                <div class="h-16 w-16 rounded-full overflow-hidden
                                            shadow-xl ring-2 ring-white">

                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/profile-photos/' . auth()->user()->profile_photo) }}"
                                             alt="{{ auth()->user()->name }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-white/20 flex items-center justify-center">
                                            <span class="text-white text-lg font-semibold">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <p class="text-sm font-semibold tracking-wide">
                                        {{ auth()->user()->name }}
                                    </p>
                                    <p class="text-xs opacity-90">
                                        {{ auth()->user()->email }}
                                    </p>
                                    <p class="text-xs mt-2 bg-white/20 inline-block px-3 py-1 rounded-full">
                                        {{ auth()->user()->role->name }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- MENU -->
                        <div class="py-3">

                            <a href="{{ route('profile') }}"
                               class="flex items-center px-6 py-3 text-sm
                                      text-gray-700 rounded-xl mx-2
                                      hover:bg-indigo-50 hover:scale-[1.02]
                                      transition-all duration-200">
                                <i class="fas fa-user-circle mr-3 text-indigo-500"></i>
                                Profile Settings
                            </a>

                            <a href="#"
                               class="flex items-center px-6 py-3 text-sm
                                      text-gray-700 rounded-xl mx-2
                                      hover:bg-indigo-50 hover:scale-[1.02]
                                      transition-all duration-200">
                                <i class="fas fa-shield-alt mr-3 text-purple-500"></i>
                                Account Security
                            </a>

                            <div class="border-t border-gray-100 my-3"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center w-full px-6 py-3 text-sm
                                               text-red-600 rounded-xl mx-2
                                               hover:bg-red-50 hover:scale-[1.02]
                                               transition-all duration-200">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Logout
                                </button>
                            </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
