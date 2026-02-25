<!-- Mobile Bottom Navigation - Modern Floating -->
<div class="lg:hidden fixed inset-x-0 bottom-0 z-50 pb-safe">
    <div class="bg-white border-t border-gray-200 shadow-2xl">
        @php
            $currentRoute = request()->route()->getName();
            $navItems = [];

            // Dashboard
            if(auth()->user()->hasPermission('dashboard.view')) {
                $navItems[] = ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-home'];
            }

            // Karyawan
            if(auth()->user()->hasPermission('karyawan.view')) {
                $navItems[] = ['label' => 'Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'];
            }

            // Payroll
            if(auth()->user()->hasPermission('acuan_gaji.view')) {
                $navItems[] = ['label' => 'Payroll', 'route' => 'payroll.acuan-gaji.index', 'icon' => 'fas fa-money-bill-wave'];
            } elseif(auth()->user()->hasPermission('hitung_gaji.view')) {
                $navItems[] = ['label' => 'Payroll', 'route' => 'payroll.hitung-gaji.index', 'icon' => 'fas fa-calculator'];
            } elseif(auth()->user()->hasPermission('slip_gaji.view')) {
                $navItems[] = ['label' => 'Payroll', 'route' => 'payroll.slip-gaji.index', 'icon' => 'fas fa-receipt'];
            }

            // Admin
            if(auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('roles.view')) {
                $navItems[] = ['label' => 'Admin', 'route' => 'admin.users.index', 'icon' => 'fas fa-cog'];
            }

            // Profile
            $navItems[] = ['label' => 'Profile', 'route' => 'profile', 'icon' => 'fas fa-user'];

            // Max 5 items
            $navItems = array_slice($navItems, 0, 5);
        @endphp

        <div class="flex items-center justify-around px-2 py-3">
            @foreach($navItems as $item)
                @php
                    $active = str_starts_with($currentRoute, str_replace('.index', '', $item['route']));
                @endphp

                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center min-w-[60px] py-2 px-3 rounded-xl transition-all duration-200
                          {{ $active ? 'bg-indigo-50' : 'hover:bg-gray-50' }}">
                    
                    <div class="relative">
                        <i class="{{ $item['icon'] }} text-xl mb-1
                                  {{ $active ? 'text-indigo-600' : 'text-gray-500' }}"></i>
                        @if($active)
                        <span class="absolute -top-1 -right-1 h-2 w-2 bg-indigo-600 rounded-full"></span>
                        @endif
                    </div>
                    
                    <span class="text-xs font-medium
                                {{ $active ? 'text-indigo-600' : 'text-gray-600' }}">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
