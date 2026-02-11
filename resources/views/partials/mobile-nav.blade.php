<!-- Mobile sidebar -->
<div class="lg:hidden" x-show="sidebarOpen" 
     x-transition:enter="transition ease-in-out duration-300"
     x-transition:enter-start="-translate-x-full" 
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300" 
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 flex w-64 z-50">
    <div class="flex-1 flex flex-col bg-white border-r border-gray-200">
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Mobile navigation header -->
            <div class="px-4 py-6 border-b border-gray-200">
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
            
            <!-- Mobile navigation -->
            <nav class="flex-1 px-4 space-y-1 mt-6">
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
                       @click="sidebarOpen = false"
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
            
            <!-- Mobile sidebar footer -->
            <div class="px-4 mt-auto py-4 border-t border-gray-200">
                <a href="{{ route('profile') }}" 
                   class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md mb-2">
                    <i class="fas fa-user-circle mr-3 text-gray-400"></i>
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>