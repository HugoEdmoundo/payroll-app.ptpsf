<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 
              bg-white border-r border-gray-200 shadow-sm">

    <!-- Wrapper -->
    <div class="flex flex-col h-full">

        <!-- Header / Brand -->
        <div class="px-6 py-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                
                <div class="h-12 w-12 rounded-xl overflow-hidden shadow-sm">
                    <img src="https://res.cloudinary.com/dfwutfkbn/image/upload/v1768808187/LOGOPSF_v4fq0w.jpg"
                         alt="Logo PSF"
                         class="h-full w-full object-cover"
                         onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<div class=\'h-9 w-9 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold\'>P</div>';">
                </div>

                <div>
                    <h1 class="text-xl font-bold leading-tight tracking-wide 
                            bg-gradient-to-r from-yellow-400 via-yellow-300 to-blue-600 
                            bg-clip-text text-transparent">
                        PAYROLL PSF
                    </h1>
                </div>

            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @php
                $role = auth()->user()->role->name;
                $currentRoute = request()->route()->getName();
                
                if ($role === 'Superadmin') {
                    $navItems = [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'],
                        ['label' => 'Data Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'],
                        ['label' => 'System Settings', 'route' => 'admin.settings.index', 'icon' => 'fas fa-cogs'],
                        ['label' => 'Manage Users', 'route' => 'admin.users.index', 'icon' => 'fas fa-users-cog'],
                        ['label' => 'Manage Roles', 'route' => 'admin.roles.index', 'icon' => 'fas fa-user-tag'],
                    ];
                } else {
                    $navItems = [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'],
                        ['label' => 'Data Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'],
                    ];
                }
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = str_contains($currentRoute, $item['route']);
                @endphp

                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg
                          transition-all duration-200
                          {{ $isActive 
                             ? 'bg-indigo-100 text-indigo-700 shadow-sm' 
                             : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">

                    <i class="{{ $item['icon'] }} w-5 mr-3
                              {{ $isActive ? 'text-indigo-600' : 'text-gray-400' }}"></i>

                    <span class="truncate">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </nav>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            <div class="text-xs text-gray-500 leading-relaxed">
                <p class="font-medium text-gray-600">Payroll PSF v1.0</p>
                <p>© {{ date('Y') }} All rights reserved</p>
            </div>
        </div>

    </div>
</aside>
