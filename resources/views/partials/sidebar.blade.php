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
                        
                        // Payroll Menu
                        ['type' => 'divider', 'label' => 'PAYROLL'],
                        ['label' => 'Pengaturan Gaji', 'route' => 'payroll.pengaturan-gaji.index', 'icon' => 'fas fa-cog'],
                        ['label' => 'Acuan Gaji', 'route' => 'payroll.acuan-gaji.index', 'icon' => 'fas fa-file-invoice'],
                        ['label' => 'NKI', 'route' => 'payroll.nki.index', 'icon' => 'fas fa-star'],
                        ['label' => 'Absensi', 'route' => 'payroll.absensi.index', 'icon' => 'fas fa-calendar-check'],
                        ['label' => 'Kasbon', 'route' => 'payroll.kasbon.index', 'icon' => 'fas fa-money-bill-wave'],
                        
                        // CMS & Admin Menu
                        ['type' => 'divider', 'label' => 'ADMIN'],
                        ['label' => 'CMS Dashboard', 'route' => 'admin.cms.index', 'icon' => 'fas fa-magic'],
                        ['label' => 'Modules', 'route' => 'admin.modules.index', 'icon' => 'fas fa-cube'],
                        ['label' => 'Dynamic Fields', 'route' => 'admin.fields.index', 'icon' => 'fas fa-list'],
                        ['label' => 'System Settings', 'route' => 'admin.settings.index', 'icon' => 'fas fa-cogs'],
                        ['label' => 'Manage Users', 'route' => 'admin.users.index', 'icon' => 'fas fa-users-cog'],
                        ['label' => 'Manage Roles', 'route' => 'admin.roles.index', 'icon' => 'fas fa-user-tag'],
                    ];
                } else {
                    $navItems = [
                        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-chart-line'],
                        ['label' => 'Data Karyawan', 'route' => 'karyawan.index', 'icon' => 'fas fa-users'],
                        
                        // User can see payroll if has permission
                        ['type' => 'divider', 'label' => 'PAYROLL'],
                        ['label' => 'Acuan Gaji', 'route' => 'payroll.acuan-gaji.index', 'icon' => 'fas fa-file-invoice'],
                    ];
                }
            @endphp

            @foreach($navItems as $item)
                @if(isset($item['type']) && $item['type'] === 'divider')
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            {{ $item['label'] }}
                        </p>
                    </div>
                @else
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
                @endif
            @endforeach
                $jenisKaryawan = \App\Models\SystemSetting::getOptions('jenis_karyawan');
            @endphp

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'dashboard') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-chart-line w-5 mr-3 {{ str_contains($currentRoute, 'dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- Data Karyawan -->
            <a href="{{ route('karyawan.index') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'karyawan') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users w-5 mr-3 {{ str_contains($currentRoute, 'karyawan') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Data Karyawan</span>
            </a>

            <!-- Pengaturan Gaji -->
            <a href="{{ route('payroll.pengaturan-gaji.index') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'pengaturan-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-money-bill-wave w-5 mr-3 {{ str_contains($currentRoute, 'pengaturan-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Pengaturan Gaji</span>
            </a>

            <!-- Komponen with Dropdown -->
            <div x-data="{ open: {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                               {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <div class="flex items-center">
                        <i class="fas fa-calculator w-5 mr-3 {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                        <span class="truncate">Komponen</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('payroll.nki.index') }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'nki') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        NKI (Tunjangan Prestasi)
                    </a>
                    <a href="{{ route('payroll.absensi.index') }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'absensi') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Absensi
                    </a>
                    <a href="{{ route('payroll.kasbon.index') }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'kasbon') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Kasbon
                    </a>
                </div>
            </div>

            <!-- Acuan Gaji with Dropdown -->
            <div x-data="{ open: {{ str_contains($currentRoute, 'acuan-gaji') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                               {{ str_contains($currentRoute, 'acuan-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <div class="flex items-center">
                        <i class="fas fa-file-invoice-dollar w-5 mr-3 {{ str_contains($currentRoute, 'acuan-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                        <span class="truncate">Acuan Gaji</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                    <a href="{{ route('payroll.acuan-gaji.index') }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
                              {{ $currentRoute === 'payroll.acuan-gaji.index' && !request('jenis_karyawan') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        Generate
                    </a>
                    @foreach($jenisKaryawan as $key => $value)
                    <a href="{{ route('payroll.acuan-gaji.index', ['jenis_karyawan' => $value]) }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200
                              {{ request('jenis_karyawan') === $value ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $value }}
                    </a>
                    @endforeach
                    <a href="{{ route('payroll.acuan-gaji.history') }}"
                       class="block px-4 py-2 text-sm rounded-lg transition-all duration-200 border-t border-gray-200 mt-2 pt-2
                              {{ $currentRoute === 'payroll.acuan-gaji.history' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-history mr-2"></i>History
                    </a>
                </div>
            </div>

            @if($role === 'Superadmin')
            <!-- System Settings -->
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'settings') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-cogs w-5 mr-3 {{ str_contains($currentRoute, 'settings') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">System Settings</span>
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'admin.users') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users-cog w-5 mr-3 {{ str_contains($currentRoute, 'admin.users') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Manage Users</span>
            </a>

            <!-- Manage Roles -->
            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'admin.roles') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-user-tag w-5 mr-3 {{ str_contains($currentRoute, 'admin.roles') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Manage Roles</span>
            </a>
            @endif
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