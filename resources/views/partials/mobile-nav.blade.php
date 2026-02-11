<!-- Mobile Bottom Navigation - Slim Liquid Glass -->
<div class="lg:hidden fixed inset-x-0 bottom-3 z-50 flex justify-center pointer-events-none">

    <div class="pointer-events-auto
                w-[96%] max-w-xs
                bg-white/5
                backdrop-blur-3xl
                border border-white/15
                shadow-lg
                rounded-[28px]
                px-2 py-1">

        @php
            $role = auth()->user()->role->name;
            $currentRoute = request()->route()->getName();

            if ($role === 'Super Admin') {
                $navItems = [
                    ['label' => 'Home', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'],
                    ['label' => 'Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'],
                    ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'fas fa-users-cog'],
                    ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fas fa-user-tag'],
                    ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => 'fas fa-cogs'],
                ];
            } else {
                $navItems = [
                    ['label' => 'Home', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'],
                    ['label' => 'Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'],
                    ['label' => 'Profile', 'route' => 'profile', 'icon' => 'fas fa-user-circle'],
                ];
            }

            // max 5
            $navItems = array_slice($navItems, 0, 5);
        @endphp

        <div class="flex items-center justify-between">
            @foreach($navItems as $item)
                @php
                    $active = str_contains($currentRoute, $item['route']);
                @endphp

                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center
                          flex-1 py-1 rounded-xl
                          transition-all duration-200">

                    <i class="{{ $item['icon'] }}
                              text-[15px]
                              transition-all duration-200
                              {{ $active
                                 ? 'text-indigo-500 scale-110'
                                 : 'text-gray-400' }}"></i>

                    <span class="text-[9px] mt-[2px]
                                 {{ $active
                                    ? 'text-indigo-500 font-medium'
                                    : 'text-gray-400' }}">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
