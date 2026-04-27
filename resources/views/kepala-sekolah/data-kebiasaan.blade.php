@extends('layouts.kepala-sekolah')

@section('title', 'SMK N 5 Telkom Banda Aceh | Data Kebiasaan')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="bg-linear-to-r from-blue-600 to-indigo-600 rounded-lg p-6 mb-6 shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Data Kebiasaan</h1>
                    <p class="text-blue-100">Monitoring penyelesaian 7 kebiasaan siswa per guru wali</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-100 mb-1">Tanggal</div>
                    <div class="text-lg font-semibold">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Guru Wali</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $guruWaliData->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $guruWaliData->sum('total_siswa') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rata-rata Penyelesaian</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $guruWaliData->count() > 0 ? round($guruWaliData->avg('presentase_penyelesaian'), 1) : 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-xl shadow-md p-5 mb-6 border border-gray-100">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Period Selector --}}
                <select id="filterPeriod" onchange="toggleFilterInputs()"
                        class="border-2 border-blue-500 text-gray-800 rounded-lg px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-600 cursor-pointer bg-white min-w-[140px] shadow-sm">
                    <option value="" {{ !request()->get('filter') ? 'selected' : '' }}>📅 Pilih Periode</option>
                    <option value="daily" {{ request()->get('filter') == 'daily' ? 'selected' : '' }}>📆 Per Hari</option>
                    <option value="weekly" {{ request()->get('filter') == 'weekly' ? 'selected' : '' }}>🗓️ Per Minggu</option>
                    <option value="monthly" {{ request()->get('filter') == 'monthly' ? 'selected' : '' }}>📅 Per Bulan</option>
                </select>

                {{-- Date Picker for Daily Filter --}}
                <div id="dailyFilter" class="{{ request()->get('filter') == 'daily' ? '' : 'hidden' }}">
                    <input type="date" id="filterDate" value="{{ request()->get('date') ?? '' }}"
                           class="border-2 border-blue-300 text-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                </div>

                {{-- Week Picker for Weekly Filter --}}
                <div id="weeklyFilter" class="{{ request()->get('filter') == 'weekly' ? '' : 'hidden' }}">
                    <select id="weekNumber"
                            class="border-2 border-indigo-300 text-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white min-w-[160px] shadow-sm">
                        <option value="">🗓️ Pilih Minggu</option>
                        @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request()->get('week') == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                        @endfor
                    </select>
                    <input type="month" id="filterMonth" value="{{ request()->get('month') ?? now()->format('Y-m') }}"
                           class="border-2 border-indigo-300 text-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                </div>

                {{-- Month Picker for Monthly Filter --}}
                <div id="monthlyFilter" class="{{ request()->get('filter') == 'monthly' ? '' : 'hidden' }}">
                    <input type="month" id="filterMonthly" value="{{ request()->get('monthly') ?? now()->format('Y-m') }}"
                           class="border-2 border-purple-300 text-gray-800 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm">
                </div>

                {{-- Search Button --}}
                <button onclick="applyFilter()"
                        class="bg-linear-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari
                </button>

                {{-- Week Logic Info (Right side of search button) --}}
                <div id="weekLogicInfo" class="{{ request()->get('filter') == 'weekly' ? '' : 'hidden' }}">
                    <div class="bg-linear-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-4 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="font-bold text-indigo-800 text-sm">Logika Minggu Bulan</p>
                        </div>
                        <div class="grid grid-cols-4 gap-2 text-xs">
                            <div class="font-semibold text-indigo-600 text-center pb-1 border-b border-indigo-200">Minggu</div>
                            <div class="font-semibold text-indigo-600 text-center pb-1 border-b border-indigo-200">31 Hari</div>
                            <div class="font-semibold text-indigo-600 text-center pb-1 border-b border-indigo-200">30 Hari</div>
                            <div class="font-semibold text-indigo-600 text-center pb-1 border-b border-indigo-200">28 Hari</div>
                            
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg">Minggu 1</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">1-7</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">1-7</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">1-7</div>
                            
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg">Minggu 2</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">8-14</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">8-14</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">8-14</div>
                            
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg">Minggu 3</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">15-21</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">15-21</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">15-21</div>
                            
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg">Minggu 4</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">22-28</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">22-28</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">22-28</div>
                            
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg">Minggu 5</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">29-31</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">29-30</div>
                            <div class="text-gray-700 text-center py-1 bg-white rounded-lg font-mono">29-28</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hidden fields tetap dipakai untuk weekly range --}}
            <input type="hidden" id="startDate" value="{{ request()->get('start_date') ?? now()->startOfWeek()->format('Y-m-d') }}">
            <input type="hidden" id="endDate" value="{{ request()->get('end_date') ?? now()->endOfWeek()->format('Y-m-d') }}">
        </div>
        
        {{-- Main Table --}}
        @if(request()->get('filter'))
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Detail Penyelesaian Kebiasaan per Guru Wali</h2>
                <p class="text-sm text-gray-600 mt-1">Data kebiasaan siswa hari ini</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIP/NIK
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Guru Wali
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Siswa
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Presentase Rata-rata Penyelesaian
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($guruWaliData as $index => $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php $user = $data['guru']->user ?? null; @endphp
                                @if($user)
                                    {{ !empty($user->nip) ? $user->nip : ($user->nik ?? '-') }}
                                @else
                                    @php $guruUser = $data['guru'] ?? null; @endphp
                                    {{ !empty($guruUser->nip) ? $guruUser->nip : ($guruUser->nik ?? '-') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10">
                                        @php $displayUser = $data['guru']->user ?? $data['guru'] ?? null; @endphp
                                        @if($displayUser && $displayUser->foto)
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                 src="{{ asset('storage/' . $displayUser->foto) }}"
                                                 alt="{{ $displayUser->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <span class="text-purple-600 font-medium text-sm">
                                                    {{ substr($displayUser->name ?? 'Unknown', 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $displayUser->name ?? 'Unknown' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $data['total_siswa'] }} siswa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center">
                                    <span class="text-lg font-semibold {{ $data['presentase_penyelesaian'] >= 80 ? 'text-green-600' : ($data['presentase_penyelesaian'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $data['presentase_penyelesaian'] }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($data['presentase_penyelesaian'] >= 80)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Sangat Baik
                                    </span>
                                @elseif($data['presentase_penyelesaian'] >= 60)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Cukup
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Perlu Perhatian
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="showDetail({{ $data['guru']->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-xs font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Belum ada data kebiasaan</p>
                                    <p class="text-gray-400 text-sm mt-1">Data kebiasaan siswa akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- Empty state when no filter selected --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Pilih Periode Filter</h3>
                    <p class="text-gray-600 text-sm max-w-md mx-auto">
                        Silakan pilih periode filter (Per Hari, Per Minggu, atau Per Bulan) untuk melihat data kebiasaan siswa.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Footer Info --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>• Presentase dihitung berdasarkan total kebiasaan yang diselesaikan oleh semua siswa guru wali tersebut.</p>
                        <p>• Setiap siswa memiliki 7 kebiasaan yang harus diselesaikan setiap hari.</p>
                        <p>• Data diperbarui secara real-time berdasarkan input kebiasaan siswa hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 w-11/12 md:w-4/5 lg:w-4/5 shadow-2xl rounded-2xl bg-white overflow-hidden transform transition-all">
        {{-- Header with Gradient --}}
        <div class="bg-linear-to-r from-blue-600 to-indigo-600 px-6 py-5">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Detail Penyelesaian Kebiasaan</h3>
                        <p class="text-blue-100 text-sm">Data kebiasaan siswa hari ini</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div id="modalContent">
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFilterInputs() {
    const filter = document.getElementById('filterPeriod').value;
    const dailyFilter = document.getElementById('dailyFilter');
    const weeklyFilter = document.getElementById('weeklyFilter');
    const monthlyFilter = document.getElementById('monthlyFilter');
    const weekLogicInfo = document.getElementById('weekLogicInfo');

    // Sembunyikan semua dulu
    dailyFilter.classList.add('hidden');
    weeklyFilter.classList.add('hidden');
    monthlyFilter.classList.add('hidden');
    weekLogicInfo.classList.add('hidden');

    // Tampilkan sesuai pilihan
    if (filter === 'daily') {
        dailyFilter.classList.remove('hidden');
    } else if (filter === 'weekly') {
        weeklyFilter.classList.remove('hidden');
        weekLogicInfo.classList.remove('hidden');
    } else if (filter === 'monthly') {
        monthlyFilter.classList.remove('hidden');
    }
    // Jika tidak dipilih (nilai kosong), keduanya tetap hidden
}

function applyFilter() {
    const filter = document.getElementById('filterPeriod').value;
    let url = `{{ route('kepala-sekolah.data-kebiasaan') }}?filter=${filter}`;
    
    if (filter === 'daily') {
        const date = document.getElementById('filterDate').value;
        if (date) {
            url += `&date=${date}`;
        }
    } else if (filter === 'weekly') {
        const week = document.getElementById('weekNumber').value;
        const month = document.getElementById('filterMonth').value;
        
        if (week) url += `&week=${week}`;
        if (month) url += `&month=${month}`;
    } else if (filter === 'monthly') {
        const monthly = document.getElementById('filterMonthly').value;
        if (monthly) url += `&monthly=${monthly}`;
    }
    
    window.location.href = url;
}

function showDetail(guruId) {
    const filter = document.getElementById('filterPeriod').value || 'daily';
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('modalContent').innerHTML = `
        <div class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
        </div>
    `;
    
    let url = `/kepala-sekolah/data-kebiasaan-detail/${guruId}?filter=${filter}`;
    
    if (filter === 'daily') {
        const date = document.getElementById('filterDate').value;
        if (date) url += `&date=${date}`;
    } else if (filter === 'weekly') {
        const week = document.getElementById('weekNumber').value;
        const month = document.getElementById('filterMonth').value;
        if (week) url += `&week=${week}`;
        if (month) url += `&month=${month}`;
    } else if (filter === 'monthly') {
        const monthly = document.getElementById('filterMonthly').value;
        if (monthly) url += `&monthly=${monthly}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderDetail(data);
            } else {
                document.getElementById('modalContent').innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <p>Gagal memuat data detail</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <p>Terjadi kesalahan: ${error.message}</p>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function renderDetail(data) {
    let html = `
        <div class="mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 mb-1">Nama Guru Wali</p>
                        <p class="text-lg font-bold text-gray-900">${data.guru_name}</p>
                        <p class="text-xs text-blue-600 font-medium">${data.period || 'Hari Ini'}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
                        <p class="text-2xl font-bold text-blue-600">${data.siswa_count}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Kebiasaan Selesai</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Presentase</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
    `;
    
    data.siswa.forEach((siswa, index) => {
        const statusColor = siswa.presentase >= 80 ? 'bg-green-100 text-green-800 border-green-200' : 
                           (siswa.presentase >= 60 ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-red-100 text-red-800 border-red-200');
        const statusText = siswa.presentase >= 80 ? 'Sangat Baik' : 
                          (siswa.presentase >= 60 ? 'Cukup' : 'Perlu Perhatian');
        const progressColor = siswa.presentase >= 80 ? 'bg-green-500' : 
                            (siswa.presentase >= 60 ? 'bg-yellow-500' : 'bg-red-500');
        
        html += `
            <tr class="hover:bg-blue-50 transition-colors">
                <td class="px-4 py-3 text-sm text-gray-900 font-medium">${index + 1}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">${siswa.nama.charAt(0)}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">${siswa.nama}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sm font-semibold text-gray-900">${siswa.kebiasaan_selesai}/${siswa.kebiasaan_maksimal || 7}</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="${progressColor} h-2 rounded-full transition-all" style="width: ${siswa.presentase}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="text-lg font-bold ${siswa.presentase >= 80 ? 'text-green-600' : (siswa.presentase >= 60 ? 'text-yellow-600' : 'text-red-600')}">${siswa.presentase}%</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ${statusColor}">
                        ${statusText}
                    </span>
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between text-sm text-gray-500">
                <p class="text-gray-400">Data diperbarui: ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
            </div>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = html;
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
