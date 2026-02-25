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
            $role = auth()->user()->role ? auth()->user()->role->name : 'No Role';
            $currentRoute = request()->route()->getName();
            $navItems = [];

            // Dashboard - selalu tampil jika ada permission
            if(auth()->user()->hasPermission('dashboard.view')) {
                $navItems[] = ['label' => 'Home', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'];
            }

            // Karyawan
            if(auth()->user()->hasPermission('karyawan.view')) {
                $navItems[] = ['label' => 'Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'];
            }

            // Payroll - prioritas untuk non-admin
            if(auth()->user()->hasPermission('acuan_gaji.view')) {
                $navItems[] = ['label' => 'Acuan', 'route' => 'payroll.acuan-gaji.index', 'icon' => 'fas fa-file-invoice-dollar'];
            } elseif(auth()->user()->hasPermission('hitung_gaji.view')) {
                $navItems[] = ['label' => 'Hitung', 'route' => 'payroll.hitung-gaji.index', 'icon' => 'fas fa-calculator'];
            } elseif(auth()->user()->hasPermission('slip_gaji.view')) {
                $navItems[] = ['label' => 'Slip', 'route' => 'payroll.slip-gaji.index', 'icon' => 'fas fa-receipt'];
            }

            // Admin menu - hanya untuk yang punya permission
            if(auth()->user()->hasPermission('users.view')) {
                $navItems[] = ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'fas fa-user-shield'];
            } elseif(auth()->user()->hasPermission('roles.view')) {
                $navItems[] = ['label' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fas fa-user-tag'];
            } elseif(auth()->user()->hasPermission('settings.view')) {
                $navItems[] = ['label' => 'Settings', 'route' => 'admin.settings.index', 'icon' => 'fas fa-sliders-h'];
            }

            // Jika masih kurang dari 5, tambahkan profile
            if(count($navItems) < 5) {
                $navItems[] = ['label' => 'Profile', 'route' => 'profile', 'icon' => 'fas fa-user-circle'];
            }

            // Max 5 items
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
