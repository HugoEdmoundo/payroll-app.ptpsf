<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:border-r lg:border-gray-200 lg:pt-16 lg:pb-4 lg:bg-gradient-to-b lg:from-white lg:to-gray-50">
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Logo/Brand -->
        <div class="px-6 py-6">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                    P
                </div>
                <div class="ml-3">
                    <h2 class="text-lg font-semibold text-gray-900">Payroll System</h2>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role->name }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1">
            @php
                $role = auth()->user()->role->name;
                $currentRoute = request()->route()->getName();
                
                if ($role === 'Super Admin') {
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
                <a href="{{ route($item['route']) }}" 
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-all duration-200
                          {{ str_contains($currentRoute, $item['route']) 
                             ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500' 
                             : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                    <i class="{{ $item['icon'] }} w-5 mr-3 flex-shrink-0 
                              {{ str_contains($currentRoute, $item['route']) 
                                 ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        
        <!-- Sidebar footer -->
        <div class="px-4 mt-auto pt-4 border-t border-gray-200">
            <div class="text-xs text-gray-500">
                <p>Payroll System v1.0</p>
                <p class="mt-1">© {{ date('Y') }} All rights reserved</p>
            </div>
        </div>
    </div>
</aside>