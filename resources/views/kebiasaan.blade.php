@extends('layouts.app')

@section('title', 'SMK N 5 Telkom Banda Aceh | 7 Kebiasaan')

@section('content')

<style>
    .hidden-field { display: none; }
    input[type="time"]::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; }
    .tab-btn { white-space: nowrap; }
</style>

<div class="min-h-screen">

    {{-- Tanggal --}}
    <div class="mb-6">
        <div class="inline-flex items-center gap-2 border border-gray-300 bg-white rounded-lg px-4 py-2 shadow-sm">
            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">
                {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- ===== TAB BAR ===== --}}
        @php
            $tabs = [
                ['id' => 'bangun_pagi',   'label' => 'Bangun pagi'],
                ['id' => 'beribadah',     'label' => 'Beribadah'],
                ['id' => 'berolahraga',   'label' => 'Berolahraga'],
                ['id' => 'makan_sehat',   'label' => 'Makan sehat'],
                ['id' => 'gemar_belajar', 'label' => 'Gemar belajar'],
                ['id' => 'bermasyarakat', 'label' => 'Bermasyarakat'],
                ['id' => 'tidur_cepat',   'label' => 'Tidur cepat'],
            ];
            $checklist = $kebiasaan->exists ? $kebiasaan->statusChecklist() : [];
        @endphp

        <div class="flex border-b border-gray-200 overflow-x-auto">
            @foreach ($tabs as $i => $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')"
                        id="tab_{{ $tab['id'] }}"
                        data-tab-id="{{ $tab['id'] }}"
                        class="tab-btn relative flex-shrink-0 px-4 py-3 text-sm font-medium border-b-2 transition-colors
                               {{ $i === 0 ? 'border-blue-600 text-blue-700 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}
                               {{ $tab['id'] === 'tidur_cepat' ? 'tidur-tab' : '' }}">
                    {{ $tab['label'] }}
                    @if ($tab['id'] === 'tidur_cepat')
                        <span id="tidur-lock-icon" class="hidden ml-1.5 text-gray-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                    @if (!empty($checklist[$tab['id']]))
                        <span class="done-dot inline-block w-1.5 h-1.5 bg-green-500 rounded-full ml-1.5 align-middle"></span>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="p-6">

            {{-- ================================================================
                 TAB 1 – BANGUN PAGI
            ================================================================ --}}
            <div id="panel_bangun_pagi" class="tab-panel">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Bangun pagi dalam Islam sangat dianjurkan karena merupakan waktu penuh berkah, rezeki, dan doa khusus dari Rasulullah SAW. Nabi bersabda,</p>
                        <p class="mt-2 italic text-gray-600">"Ya Allah, berkahilah umatku di waktu paginya" <strong>(HR. Abu Dawud)</strong>.</p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        {{-- Apakah bangun pagi --}}
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Apakah kamu bangun pagi?</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="bp_status" value="iya"
                                           {{ $kebiasaan->bangun_pagi === true ? 'checked' : '' }}
                                           onchange="toggleShow('bp_jam_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="bp_status" value="tidak"
                                           {{ $kebiasaan->bangun_pagi === false ? 'checked' : '' }}
                                           onchange="toggleShow('bp_jam_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        {{-- Jam bangun — muncul hanya jika "iya" --}}
                        <div id="bp_jam_section" class="{{ $kebiasaan->bangun_pagi === true ? '' : 'hidden-field' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam berapa bangun?</label>
                            <input type="time" id="bp_jam" name="bp_jam"
                                   value="{{ $kebiasaan->jam_bangun ? \Carbon\Carbon::parse($kebiasaan->jam_bangun)->format('H:i') : '05:30' }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                          focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="bp_catatan" name="bp_catatan" rows="4" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->bangun_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('bangun_pagi')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

            {{-- ================================================================
                 TAB 2 – BERIBADAH
            ================================================================ --}}
            <div id="panel_beribadah" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Kebiasaan beribadah bukanlah pilihan sampingan, melainkan alasan utama kita diciptakan. Allah berfirman:</p>
                        <p class="mt-2 italic text-gray-600">"Dan Aku tidak menciptakan jin dan manusia melainkan supaya mereka mengabdi (beribadah) kepada-Ku." <strong>(QS. Adz-Dzariyat: 56)</strong></p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        {{-- Sholat 5 waktu + jam masing-masing --}}
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">Sholat 5 waktu</p>
                            <div class="space-y-2">
                                @php
                                    $sholatList = [
                                        'subuh'   => ['label' => 'Subuh',   'default' => '04:30', 'field' => 'sholat_subuh',   'jam' => 'jam_sholat_subuh'],
                                        'dzuhur'  => ['label' => 'Dzuhur',  'default' => '12:30', 'field' => 'sholat_dzuhur',  'jam' => 'jam_sholat_dzuhur'],
                                        'ashar'   => ['label' => 'Ashar',   'default' => '15:30', 'field' => 'sholat_ashar',   'jam' => 'jam_sholat_ashar'],
                                        'maghrib' => ['label' => 'Maghrib', 'default' => '18:15', 'field' => 'sholat_maghrib', 'jam' => 'jam_sholat_maghrib'],
                                        'isya'    => ['label' => 'Isya',    'default' => '19:30', 'field' => 'sholat_isya',    'jam' => 'jam_sholat_isya'],
                                    ];
                                @endphp

                                @foreach ($sholatList as $key => $s)
                                    @php $isChecked = $kebiasaan->{$s['field']} ?? false; @endphp
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <label class="flex items-center gap-2 cursor-pointer w-28">
                                                <input type="checkbox"
                                                       name="sholat[]"
                                                       value="{{ $key }}"
                                                       id="cb_sholat_{{ $key }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       onchange="toggleShow('jam_{{ $key }}_row', this.checked)"
                                                       class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                                <span class="text-sm text-gray-700">{{ $s['label'] }}</span>
                                            </label>
                                            {{-- Jam sholat — muncul jika dicentang --}}
                                            <div id="jam_{{ $key }}_row"
                                                 class="flex items-center gap-2 {{ $isChecked ? '' : 'hidden-field' }}">
                                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <input type="time"
                                                       id="jam_{{ $key }}"
                                                       name="jam_{{ $key }}"
                                                       value="{{ $kebiasaan->{$s['jam']} ? \Carbon\Carbon::parse($kebiasaan->{$s['jam']})->format('H:i') : $s['default'] }}"
                                                       class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-gray-800
                                                              focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Baca Al-Quran --}}
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Baca Al-Quran</p>
                            <div class="flex items-center gap-6 mb-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="quran_status" value="iya"
                                           {{ $kebiasaan->baca_quran === true ? 'checked' : '' }}
                                           onchange="toggleShow('quran_surah_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="quran_status" value="tidak"
                                           {{ $kebiasaan->baca_quran === false ? 'checked' : '' }}
                                           onchange="toggleShow('quran_surah_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>

                            {{-- Pilih surah — muncul hanya jika "iya" --}}
                            <div id="quran_surah_section" class="{{ $kebiasaan->baca_quran === true ? '' : 'hidden-field' }}">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Surah yang dibaca</label>
                                <select name="quran_surah"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Pilih Surah --</option>
                                    @php
                                        $surahList = [
                                            1=>'Al-Fatihah', 2=>'Al-Baqarah', 3=>'Ali Imran', 4=>'An-Nisa',
                                            5=>'Al-Maidah', 6=>'Al-Anam', 7=>'Al-Araf', 8=>'Al-Anfal',
                                            9=>'At-Taubah', 10=>'Yunus', 11=>'Hud', 12=>'Yusuf',
                                            13=>'Ar-Rad', 14=>'Ibrahim', 15=>'Al-Hijr', 16=>'An-Nahl',
                                            17=>'Al-Isra', 18=>'Al-Kahf', 19=>'Maryam', 20=>'Ta-Ha',
                                            21=>'Al-Anbiya', 22=>'Al-Hajj', 23=>'Al-Muminun', 24=>'An-Nur',
                                            25=>'Al-Furqan', 26=>'Asy-Syuara', 27=>'An-Naml', 28=>'Al-Qasas',
                                            29=>'Al-Ankabut', 30=>'Ar-Rum', 31=>'Luqman', 32=>'As-Sajdah',
                                            33=>'Al-Ahzab', 34=>'Saba', 35=>'Fatir', 36=>'Ya-Sin',
                                            37=>'As-Saffat', 38=>'Sad', 39=>'Az-Zumar', 40=>'Ghafir',
                                            41=>'Fussilat', 42=>'Asy-Syura', 43=>'Az-Zukhruf', 44=>'Ad-Dukhan',
                                            45=>'Al-Jasiyah', 46=>'Al-Ahqaf', 47=>'Muhammad', 48=>'Al-Fath',
                                            49=>'Al-Hujurat', 50=>'Qaf', 51=>'Az-Zariyat', 52=>'At-Tur',
                                            53=>'An-Najm', 54=>'Al-Qamar', 55=>'Ar-Rahman', 56=>'Al-Waqiah',
                                            57=>'Al-Hadid', 58=>'Al-Mujadilah', 59=>'Al-Hasyr', 60=>'Al-Mumtahanah',
                                            61=>'As-Saf', 62=>'Al-Jumuah', 63=>'Al-Munafiqun', 64=>'At-Taghabun',
                                            65=>'At-Talaq', 66=>'At-Tahrim', 67=>'Al-Mulk', 68=>'Al-Qalam',
                                            69=>'Al-Haqqah', 70=>'Al-Maarij', 71=>'Nuh', 72=>'Al-Jin',
                                            73=>'Al-Muzzammil', 74=>'Al-Muddassir', 75=>'Al-Qiyamah', 76=>'Al-Insan',
                                            77=>'Al-Mursalat', 78=>'An-Naba', 79=>'An-Naziat', 80=>'Abasa',
                                            81=>'At-Takwir', 82=>'Al-Infitar', 83=>'Al-Mutaffifin', 84=>'Al-Insyiqaq',
                                            85=>'Al-Buruj', 86=>'At-Tariq', 87=>'Al-Ala', 88=>'Al-Ghasyiyah',
                                            89=>'Al-Fajr', 90=>'Al-Balad', 91=>'Asy-Syams', 92=>'Al-Lail',
                                            93=>'Ad-Duha', 94=>'Al-Insyirah', 95=>'At-Tin', 96=>'Al-Alaq',
                                            97=>'Al-Qadr', 98=>'Al-Bayyinah', 99=>'Az-Zalzalah', 100=>'Al-Adiyat',
                                            101=>'Al-Qariah', 102=>'At-Takasur', 103=>'Al-Asr', 104=>'Al-Humazah',
                                            105=>'Al-Fil', 106=>'Quraisy', 107=>'Al-Maun', 108=>'Al-Kausar',
                                            109=>'Al-Kafirun', 110=>'An-Nasr', 111=>'Al-Masad', 112=>'Al-Ikhlas',
                                            113=>'Al-Falaq', 114=>'An-Nas',
                                        ];
                                    @endphp
                                    @foreach ($surahList as $no => $nama)
                                        <option value="{{ $no }}" {{ $kebiasaan->quran_surah == $no ? 'selected' : '' }}>
                                            {{ $no }}. {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="ib_catatan" name="ib_catatan" rows="3" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->ibadah_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('beribadah')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

            {{-- ================================================================
                 TAB 3 – BEROLAHRAGA
            ================================================================ --}}
            <div id="panel_berolahraga" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Kesehatan bukan milik kita sepenuhnya, melainkan amanah. Berolahraga adalah cara memenuhi hak tubuh tersebut.</p>
                        <p class="mt-2 italic text-gray-600">"Sesungguhnya tubuhmu memiliki hak atas dirimu." <strong>(HR. Bukhari)</strong>.</p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Apakah kamu berolahraga?</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ol_status" value="iya"
                                           {{ $kebiasaan->berolahraga === true ? 'checked' : '' }}
                                           onchange="toggleShow('ol_detail_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="ol_status" value="tidak"
                                           {{ $kebiasaan->berolahraga === false ? 'checked' : '' }}
                                           onchange="toggleShow('ol_detail_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        {{-- Detail olahraga — muncul hanya jika "iya" --}}
                        <div id="ol_detail_section" class="{{ $kebiasaan->berolahraga === true ? '' : 'hidden-field' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis olahraga & catatan</label>

                            <div id="olahragaList" class="space-y-3">
                                @php
                                    $jenisOlahraga = $kebiasaan->jenis_olahraga ?? [['jenis' => 'padel', 'catatan' => '']];
                                    $opsiOlahraga  = ['padel','lari','renang','sepak bola','basket','voli','badminton','senam','bersepeda','lainnya'];
                                @endphp

                                @foreach ($jenisOlahraga as $ol)
                                    @php
                                        $jenisFill   = is_array($ol) ? ($ol['jenis'] ?? $ol) : $ol;
                                        $catatanFill = is_array($ol) ? ($ol['catatan'] ?? '') : '';
                                    @endphp
                                    <div class="olahraga-item border border-gray-200 rounded-lg p-3 bg-gray-50 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <select name="ol_jenis[]"
                                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                                                           focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                                @foreach ($opsiOlahraga as $opsi)
                                                    <option value="{{ $opsi }}" {{ $jenisFill === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" onclick="hapusOlahraga(this)"
                                                    class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <textarea name="ol_catatan[]" rows="2" required
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                                         focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                                  placeholder="Catatan untuk olahraga ini...">{{ $catatanFill }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Catatan umum (selalu tampil) --}}
                        <div id="ol_catatan_umum_section" class="{{ $kebiasaan->berolahraga === false ? '' : 'hidden-field' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="ol_catatan_umum" name="ol_catatan_umum" rows="4" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->olahraga_catatan }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                    <div id="tambah_olahraga_btn" class="{{ $kebiasaan->berolahraga === true ? '' : 'hidden-field' }}">
                        <button type="button" onclick="tambahOlahraga()"
                                class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium
                                       text-gray-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah olahraga
                        </button>
                    </div>
                    <div class="ml-auto">
                        <button onclick="kirimKebiasaan('berolahraga')"
                                class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                            Kirim
                        </button>
                    </div>
                </div>
            </div>

            {{-- ================================================================
                 TAB 4 – MAKAN SEHAT
            ================================================================ --}}
            <div id="panel_makan_sehat" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Al-Qur'an memerintahkan kita tidak hanya mencari yang halal secara hukum, tapi juga yang <em>thayyib</em> (bergizi dan bersih) bagi tubuh.</p>
                        <p class="mt-2 italic text-gray-600">"Wahai manusia! Makanlah dari (makanan) yang halal dan baik (thayyib) yang terdapat di bumi..." <strong>(QS. Al-Baqarah: 168)</strong></p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">Apakah kamu makan sehat hari ini?</p>
                            <div class="flex items-center gap-8">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="mk_status" value="iya"
                                           {{ $kebiasaan->makan_sehat === true ? 'checked' : '' }}
                                           onchange="toggleShow('mk_detail_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="mk_status" value="tidak"
                                           {{ $kebiasaan->makan_sehat === false ? 'checked' : '' }}
                                           onchange="toggleShow('mk_detail_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        {{-- Detail 3 waktu makan — muncul hanya jika "iya" --}}
                        <div id="mk_detail_section" class="{{ $kebiasaan->makan_sehat === true ? '' : 'hidden-field' }} space-y-5">
                            @foreach ([
                                ['key' => 'pagi',  'label' => 'Makan pagi dengan apa?',  'val' => $kebiasaan->makan_pagi,  'done' => $kebiasaan->makan_pagi_done],
                                ['key' => 'siang', 'label' => 'Makan siang dengan apa?', 'val' => $kebiasaan->makan_siang, 'done' => $kebiasaan->makan_siang_done],
                                ['key' => 'malam', 'label' => 'Makan malam dengan apa?', 'val' => $kebiasaan->makan_malam, 'done' => $kebiasaan->makan_malam_done],
                            ] as $makan)
                                <div>
                                    <div class="flex items-center gap-3 mb-3">
                                        {{-- Checkbox kanan: jika dicentang → input aktif --}}
                                        <label class="flex items-center gap-2 cursor-pointer flex-shrink-0">
                                            <input type="checkbox"
                                                   id="cb_mk_{{ $makan['key'] }}"
                                                   name="mk_{{ $makan['key'] }}_done"
                                                   value="1"
                                                   {{ $makan['done'] ? 'checked' : '' }}
                                                   onchange="toggleInputEnabled('inp_mk_{{ $makan['key'] }}', this.checked)"
                                                   class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                            <span class="text-sm font-medium text-gray-700">{{ $makan['label'] }}</span>
                                        </label>
                                    </div>
                                    <input type="text"
                                           id="inp_mk_{{ $makan['key'] }}"
                                           name="mk_{{ $makan['key'] }}"
                                           value="{{ old('mk_' . $makan['key'], $makan['val']) }}"
                                           {{ !$makan['done'] ? 'disabled' : '' }}
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800
                                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                                  disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed
                                                  transition-colors"
                                           placeholder="{{ $makan['done'] ? 'Contoh: nasi goreng, roti...' : 'Centang untuk mengisi...' }}"/>
                                </div>
                            @endforeach
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea id="mk_catatan" name="mk_catatan" rows="3" required
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->makan_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('makan_sehat')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

            {{-- ================================================================
                 TAB 5 – GEMAR BELAJAR
            ================================================================ --}}
            <div id="panel_gemar_belajar" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Belajar bukan pilihan, melainkan tugas setiap individu sepanjang hayat untuk mengenal pencipta dan dunianya.</p>
                        <p class="mt-2 italic text-gray-600">"Menuntut ilmu itu wajib atas setiap muslim." <strong>(HR. Ibnu Majah)</strong></p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Apakah kamu belajar hari ini?</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="bl_status" value="iya"
                                           {{ $kebiasaan->gemar_belajar === true ? 'checked' : '' }}
                                           onchange="toggleShow('bl_pelajaran_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="bl_status" value="tidak"
                                           {{ $kebiasaan->gemar_belajar === false ? 'checked' : '' }}
                                           onchange="toggleShow('bl_pelajaran_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        {{-- Apa yang dipelajari — muncul hanya jika "iya" --}}
                        <div id="bl_pelajaran_section" class="{{ $kebiasaan->gemar_belajar === true ? '' : 'hidden-field' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apa yang kamu pelajari?</label>
                            <input type="text" name="bl_pelajaran"
                                   value="{{ old('bl_pelajaran', $kebiasaan->materi_belajar) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                          focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: matematika, pemrograman web..."/>
                        </div>

                        {{-- Catatan (selalu tampil) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="bl_catatan" name="bl_catatan" rows="5" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->belajar_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('gemar_belajar')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

            {{-- ================================================================
                 TAB 6 – BERMASYARAKAT
            ================================================================ --}}
            <div id="panel_bermasyarakat" class="tab-panel hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Kebiasaan membantu sesama dan memberi solusi bagi masalah sosial adalah amalan yang sangat mulia.</p>
                        <p class="mt-2 italic text-gray-600">"Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya." <strong>(HR. Ahmad)</strong></p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Dengan siapa kamu bermasyarakat hari ini?</p>
                            @php $bersamaData = $kebiasaan->bersama ?? []; @endphp
                            <div class="flex flex-wrap gap-4">
                                @foreach (['keluarga' => 'Keluarga', 'teman' => 'Teman', 'tetangga' => 'Tetangga', 'publik' => 'Publik'] as $val => $label)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="ms_dengan[]" value="{{ $val }}"
                                               {{ in_array($val, $bersamaData) ? 'checked' : '' }}
                                               class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="ms_catatan" name="ms_catatan" rows="7" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->masyarakat_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('bermasyarakat')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

            {{-- ================================================================
                 TAB 7 – TIDUR CEPAT
            ================================================================ --}}
            <div id="panel_tidur_cepat" class="tab-panel hidden">
                {{-- Locked message overlay --}}
                <div id="tidur_locked_message" class="hidden bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-2xl p-8 text-center shadow-lg">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-amber-800 mb-3">Form Terkunci</h3>
                    <p class="text-amber-700 text-sm mb-4">Form "Tidur Cepat" baru bisa diisi mulai <strong>jam 8 malam</strong>.</p>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl px-5 py-4 mb-4 text-left shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center shrink-0 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-800 mb-1">Informasi Penting</p>
                                <p class="text-xs text-blue-700">
                                    Waktu tidur cepat dianjurkan mulai jam <strong>21:00 - 22:00</strong> (9 PM - 10 PM)
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-amber-600 text-xs">Silakan kembali lagi setelah jam 8 malam untuk mengisi form.</p>
                </div>

                {{-- Form content (will be disabled if locked) --}}
                <div id="tidur_form_content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kutipan --}}
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed self-start">
                        <p>Rasulullah mengajarkan untuk segera beristirahat setelah menunaikan kewajiban hari itu (Isya), agar tidak membuang waktu untuk hal yang sia-sia.</p>
                        <p class="mt-2 italic text-gray-600">"Rasulullah SAW membenci tidur sebelum salat Isya dan bincang-bincang (yang tidak bermanfaat) setelahnya." <strong>(HR. Bukhari &amp; Muslim)</strong></p>
                    </div>

                    {{-- Form --}}
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Apakah kamu tidur cepat?</p>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tc_status" value="iya"
                                           {{ $kebiasaan->tidur_cepat === true ? 'checked' : '' }}
                                           onchange="toggleShow('tc_jam_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Iya</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="tc_status" value="tidak"
                                           {{ $kebiasaan->tidur_cepat === false ? 'checked' : '' }}
                                           onchange="toggleShow('tc_jam_section', this.value === 'iya')"
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"/>
                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                        </div>

                        {{-- Jam tidur — muncul hanya jika "iya" --}}
                        <div id="tc_jam_section" class="{{ $kebiasaan->tidur_cepat === true ? '' : 'hidden-field' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam berapa kamu tidur?</label>
                            <input type="time" id="tc_jam" name="tc_jam"
                                   value="{{ $kebiasaan->jam_tidur ? \Carbon\Carbon::parse($kebiasaan->jam_tidur)->format('H:i') : '21:30' }}"
                                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                          focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        {{-- Catatan (selalu tampil) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="tc_catatan" name="tc_catatan" rows="5" required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                             focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                      placeholder="Tuliskan catatan...">{{ $kebiasaan->tidur_catatan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
                    <button onclick="kirimKebiasaan('tidur_cepat')"
                            class="px-6 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-sm font-medium text-gray-700 rounded-lg transition-colors shadow-sm">
                        Kirim
                    </button>
                </div>
            </div>

        </div>{{-- end panel wrapper --}}
    </div>{{-- end container --}}
</div>{{-- end page --}}

{{-- Toast --}}
<div id="toast"
     class="fixed top-5 right-5 z-[9999] flex items-center gap-2 text-white text-sm font-medium
            px-4 py-3 rounded-xl shadow-lg opacity-0 -translate-y-2 pointer-events-none transition-all duration-300">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="toastMsg"></span>
</div>

<script>
const TAB_IDS = ['bangun_pagi','beribadah','berolahraga','makan_sehat','gemar_belajar','bermasyarakat','tidur_cepat'];

/* ── Ganti Tanggal ────────────────────────────────────────── */
function gantiTanggal(tanggalBaru) {
    // Redirect ke URL dengan parameter tanggal baru
    const url = new URL(window.location);
    url.searchParams.set('tanggal', tanggalBaru);
    window.location.href = url.toString();
}

/* ── Tab switching ─────────────────────────────────────── */
function switchTab(id) {
    TAB_IDS.forEach(tid => {
        const panel = document.getElementById('panel_' + tid);
        const btn   = document.getElementById('tab_' + tid);
        if (tid === id) {
            panel?.classList.remove('hidden');
            btn?.classList.remove('border-transparent','text-gray-500');
            btn?.classList.add('border-blue-600','text-blue-700','bg-blue-50');
        } else {
            panel?.classList.add('hidden');
            btn?.classList.remove('border-blue-600','text-blue-700','bg-blue-50');
            btn?.classList.add('border-transparent','text-gray-500');
        }
    });
}

/* ── Show / hide helper ────────────────────────────────── */
function toggleShow(id, show) {
    const el = document.getElementById(id);
    if (!el) return;
    if (show) {
        el.classList.remove('hidden-field');
    } else {
        el.classList.add('hidden-field');
    }
}

/* ── Olahraga: tambah juga toggle show/hide tombol ────── */
function tambahOlahraga() {
    const list = document.getElementById('olahragaList');
    const opsi = ['padel','lari','renang','sepak bola','basket','voli','badminton','senam','bersepeda','lainnya'];
    const item = document.createElement('div');
    item.className = 'olahraga-item border border-gray-200 rounded-lg p-3 bg-gray-50 space-y-2';
    item.innerHTML = `
        <div class="flex items-center gap-2">
            <select name="ol_jenis[]"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                ${opsi.map(o => `<option value="${o}">${o}</option>`).join('')}
            </select>
            <button type="button" onclick="hapusOlahraga(this)"
                    class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <textarea name="ol_catatan[]" rows="2" required
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                         focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                  placeholder="Catatan untuk olahraga ini..."></textarea>`;
    list.appendChild(item);
}

function hapusOlahraga(btn) {
    const list = document.getElementById('olahragaList');
    if (list.querySelectorAll('.olahraga-item').length > 1) {
        btn.closest('.olahraga-item').remove();
    }
}

/* ── Olahraga radio: sync show detail + tombol tambah ─── */
document.querySelectorAll('input[name="ol_status"]').forEach(el => {
    el.addEventListener('change', function () {
        const isIya = this.value === 'iya';
        toggleShow('ol_detail_section',     isIya);
        toggleShow('tambah_olahraga_btn',   isIya);
        toggleShow('ol_catatan_umum_section', !isIya);
    });
});

/* ── Enable / disable input makan sehat ──────────────── */
function toggleInputEnabled(inputId, enabled) {
    const inp = document.getElementById(inputId);
    if (!inp) return;
    inp.disabled    = !enabled;
    inp.placeholder = enabled ? 'Contoh: nasi goreng, roti...' : 'Centang untuk mengisi...';
    if (!enabled) inp.value = '';
}

/* ── Kirim kebiasaan ke backend ──────────────────────── */
function kirimKebiasaan(section) {
    const data = { section, tanggal: '{{ $tanggal }}' };

    switch (section) {
        case 'bangun_pagi':
            data.status  = document.querySelector('input[name="bp_status"]:checked')?.value ?? null;
            data.jam     = document.getElementById('bp_jam')?.value || null;
            data.catatan = document.getElementById('bp_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('bp_catatan').focus();
                return;
            }
            break;

        case 'beribadah':
            data.sholat = {};
            ['subuh','dzuhur','ashar','maghrib','isya'].forEach(w => {
                data.sholat[w] = document.getElementById('cb_sholat_' + w)?.checked ? 1 : 0;
                data['jam_' + w] = document.getElementById('jam_' + w)?.value || null;
            });
            data.quran   = document.querySelector('input[name="quran_status"]:checked')?.value ?? null;
            data.surah   = document.querySelector('select[name="quran_surah"]')?.value || null;
            data.catatan = document.getElementById('ib_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('ib_catatan').focus();
                return;
            }
            break;

        case 'berolahraga':
            data.status = document.querySelector('input[name="ol_status"]:checked')?.value ?? null;
            if (data.status === 'iya') {
                const jenis   = [...document.querySelectorAll('select[name="ol_jenis[]"]')].map(e => e.value);
                const catatan = [...document.querySelectorAll('textarea[name="ol_catatan[]"]')].map(e => e.value);
                // Validasi catatan olahraga
                for (let i = 0; i < catatan.length; i++) {
                    if (!catatan[i]?.trim()) {
                        tampilkanToast('Catatan untuk setiap olahraga wajib diisi!', 'red');
                        document.querySelectorAll('textarea[name="ol_catatan[]"]')[i].focus();
                        return;
                    }
                }
                data.jenis    = jenis.map((j, i) => ({ jenis: j, catatan: catatan[i] || '' }));
            } else {
                data.catatan = document.getElementById('ol_catatan_umum')?.value;
                if (!data.catatan?.trim()) {
                    tampilkanToast('Catatan wajib diisi!', 'red');
                    document.getElementById('ol_catatan_umum').focus();
                    return;
                }
            }
            break;

        case 'makan_sehat':
            data.status     = document.querySelector('input[name="mk_status"]:checked')?.value ?? null;
            data.pagi       = document.querySelector('input[name="mk_pagi"]')?.value || null;
            data.pagi_done  = document.getElementById('cb_mk_pagi')?.checked ? 1 : 0;
            data.siang      = document.querySelector('input[name="mk_siang"]')?.value || null;
            data.siang_done = document.getElementById('cb_mk_siang')?.checked ? 1 : 0;
            data.malam      = document.querySelector('input[name="mk_malam"]')?.value || null;
            data.malam_done = document.getElementById('cb_mk_malam')?.checked ? 1 : 0;
            data.catatan    = document.getElementById('mk_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('mk_catatan').focus();
                return;
            }
            break;

        case 'gemar_belajar':
            data.status    = document.querySelector('input[name="bl_status"]:checked')?.value ?? null;
            data.pelajaran = document.querySelector('input[name="bl_pelajaran"]')?.value || null;
            data.catatan   = document.getElementById('bl_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('bl_catatan').focus();
                return;
            }
            break;

        case 'bermasyarakat':
            data.dengan  = [...document.querySelectorAll('input[name="ms_dengan[]"]:checked')].map(e => e.value);
            data.catatan = document.getElementById('ms_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('ms_catatan').focus();
                return;
            }
            break;

        case 'tidur_cepat':
            data.status  = document.querySelector('input[name="tc_status"]:checked')?.value ?? null;
            data.jam     = document.getElementById('tc_jam')?.value || null;
            data.catatan = document.getElementById('tc_catatan')?.value;
            if (!data.catatan?.trim()) {
                tampilkanToast('Catatan wajib diisi!', 'red');
                document.getElementById('tc_catatan').focus();
                return;
            }
            break;
    }

    fetch('{{ route("student.kebiasaan.store") }}', {
        method : 'POST',
        headers: {
            'Content-Type' : 'application/json',
            'X-CSRF-TOKEN' : '{{ csrf_token() }}',
            'Accept'       : 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            tampilkanToast('Data ' + section.replace(/_/g,' ') + ' berhasil disimpan!', 'green');
            const btn = document.getElementById('tab_' + section);
            if (btn && !btn.querySelector('.done-dot')) {
                const dot = document.createElement('span');
                dot.className = 'done-dot inline-block w-1.5 h-1.5 bg-green-500 rounded-full ml-1.5 align-middle';
                btn.appendChild(dot);
            }

            // Auto-forward to next section
            const currentIndex = TAB_IDS.indexOf(section);
            if (currentIndex !== -1 && currentIndex < TAB_IDS.length - 1) {
                const nextSection = TAB_IDS[currentIndex + 1];
                setTimeout(() => switchTab(nextSection), 500);
            }
        } else {
            tampilkanToast('Gagal: ' + (res.message ?? 'Terjadi kesalahan'), 'red');
        }
    })
    .catch(() => tampilkanToast('Gagal terhubung ke server.', 'red'));
}

/* ── Toast ──────────────────────────────────────────────── */
function tampilkanToast(pesan, warna = 'green') {
    const toast = document.getElementById('toast');
    const msg   = document.getElementById('toastMsg');
    toast.classList.remove('bg-green-600','bg-red-600');
    toast.classList.add(warna === 'red' ? 'bg-red-600' : 'bg-green-600');
    msg.textContent = pesan;
    toast.classList.remove('opacity-0','-translate-y-2','pointer-events-none');
    toast.classList.add('opacity-100','translate-y-0');
    setTimeout(() => {
        toast.classList.add('opacity-0','-translate-y-2','pointer-events-none');
        toast.classList.remove('opacity-100','translate-y-0');
    }, 3000);
}

/* ── Tidur Cepat Time Lock ────────────────────────────────── */
function checkTidurCepatLock() {
    const now = new Date();
    const currentHour = now.getHours();
    const isAfter8PM = currentHour >= 20; // 20:00 = 8 PM

    const tabBtn = document.getElementById('tab_tidur_cepat');
    const lockIcon = document.getElementById('tidur-lock-icon');
    const lockedMessage = document.getElementById('tidur_locked_message');
    const formContent = document.getElementById('tidur_form_content');

    if (isAfter8PM) {
        // Unlock the tab
        tabBtn.disabled = false;
        tabBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        tabBtn.onclick = () => switchTab('tidur_cepat');
        lockIcon.classList.add('hidden');
        lockedMessage.classList.add('hidden');
        formContent.classList.remove('opacity-50', 'pointer-events-none');
    } else {
        // Allow tab to be clicked but show locked message
        tabBtn.disabled = false;
        tabBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        tabBtn.onclick = () => {
            switchTab('tidur_cepat');
            lockIcon.classList.remove('hidden');
            lockedMessage.classList.remove('hidden');
            formContent.classList.add('opacity-50', 'pointer-events-none');
        };
        lockIcon.classList.remove('hidden');
        lockedMessage.classList.remove('hidden');
        formContent.classList.add('opacity-50', 'pointer-events-none');
    }
}

// Check on page load
document.addEventListener('DOMContentLoaded', checkTidurCepatLock);

// Check every minute to auto-unlock at 8 PM
setInterval(checkTidurCepatLock, 60000);
</script>

@endsection