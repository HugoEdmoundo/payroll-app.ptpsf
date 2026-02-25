@php
    $role = auth()->user()->role ? auth()->user()->role->name : 'No Role';
    $currentRoute = request()->route()->getName();
    $jenisKaryawan = \App\Models\SystemSetting::getOptions('jenis_karyawan');
@endphp

<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 
              bg-white border-r border-gray-200 shadow-sm">

    <!-- Wrapper -->
    <div class="flex flex-col h-full">

        <!-- Header / Brand -->
        <div class="px-6 py-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                
                <div class="h-12 w-12 rounded-xl overflow-hidden shadow-sm">
                    <img src="{{ asset('images/LOGOPSF_v4fq0w.jpg') }}"
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
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            @if(auth()->user()->hasPermission('dashboard.view'))
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'dashboard') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-chart-line w-5 text-center mr-3 {{ str_contains($currentRoute, 'dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Dashboard</span>
            </a>
            @endif

            <!-- Data Karyawan -->
            @if(auth()->user()->hasPermission('karyawan.view'))
            <a href="{{ route('karyawan.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'karyawan') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users w-5 text-center mr-3 {{ str_contains($currentRoute, 'karyawan') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Data Karyawan</span>
            </a>
            @endif

            <!-- Pengaturan Gaji -->
            @if(auth()->user()->hasPermission('pengaturan_gaji.view'))
            <a href="{{ route('payroll.pengaturan-gaji.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'pengaturan-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-money-bill-wave w-5 text-center mr-3 {{ str_contains($currentRoute, 'pengaturan-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Pengaturan Gaji</span>
            </a>
            @endif

            <!-- Komponen with Dropdown -->
            @if(auth()->user()->hasPermission('nki.view') || auth()->user()->hasPermission('absensi.view') || auth()->user()->hasPermission('kasbon.view'))
            <div x-data="{ open: {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                               {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                    <div class="flex items-center">
                        <i class="fas fa-calculator w-5 text-center mr-3 {{ str_contains($currentRoute, 'nki') || str_contains($currentRoute, 'absensi') || str_contains($currentRoute, 'kasbon') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                        <span class="truncate">Komponen</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                    @if(auth()->user()->hasPermission('nki.view'))
                    <a href="{{ route('payroll.nki.index') }}"
                       class="block px-3 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'nki') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        NKI (Tunjangan Prestasi)
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('absensi.view'))
                    <a href="{{ route('payroll.absensi.index') }}"
                       class="block px-3 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'absensi') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        Absensi
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('kasbon.view'))
                    <a href="{{ route('payroll.kasbon.index') }}"
                       class="block px-3 py-2 text-sm rounded-lg transition-all duration-200
                              {{ str_contains($currentRoute, 'kasbon') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        Kasbon
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Acuan Gaji -->
            @if(auth()->user()->hasPermission('acuan_gaji.view'))
            <a href="{{ route('payroll.acuan-gaji.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'acuan-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-file-invoice-dollar w-5 text-center mr-3 {{ str_contains($currentRoute, 'acuan-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Acuan Gaji</span>
            </a>
            @endif

            <!-- Hitung Gaji -->
            @if(auth()->user()->hasPermission('hitung_gaji.view'))
            <a href="{{ route('payroll.hitung-gaji.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'hitung-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-calculator w-5 text-center mr-3 {{ str_contains($currentRoute, 'hitung-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Hitung Gaji</span>
            </a>
            @endif

            <!-- Slip Gaji -->
            @if(auth()->user()->hasPermission('slip_gaji.view'))
            <a href="{{ route('payroll.slip-gaji.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'slip-gaji') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-file-invoice w-5 text-center mr-3 {{ str_contains($currentRoute, 'slip-gaji') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Slip Gaji</span>
            </a>
            @endif

            <!-- Divider -->
            @if(auth()->user()->hasPermission('settings.view') || auth()->user()->hasPermission('users.view') || auth()->user()->hasPermission('roles.view'))
            <div class="pt-4 pb-2">
                <div class="border-t border-gray-200"></div>
            </div>
            @endif

            <!-- System Settings -->
            @if(auth()->user()->hasPermission('settings.view'))
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'settings') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-cogs w-5 text-center mr-3 {{ str_contains($currentRoute, 'settings') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">System Settings</span>
            </a>
            @endif

            <!-- Manage Users -->
            @if(auth()->user()->hasPermission('users.view'))
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'admin.users') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-users-cog w-5 text-center mr-3 {{ str_contains($currentRoute, 'admin.users') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span class="truncate">Manage Users</span>
            </a>
            @endif

            <!-- Manage Roles -->
            @if(auth()->user()->hasPermission('roles.view'))
            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200
                      {{ str_contains($currentRoute, 'admin.roles') ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas fa-user-tag w-5 text-center mr-3 {{ str_contains($currentRoute, 'admin.roles') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
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
