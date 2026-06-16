@php $prefix = $edit ?? false ? 'edit_' : ''; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Kuis <span
                class="text-red-500">*</span></label>
        <input type="text" name="judul" id="{{ $prefix }}judul" required placeholder="Contoh: Literasi 4"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori <span
                class="text-red-500">*</span></label>
        <select name="kategori" id="{{ $prefix }}kategori" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="literasi">Literasi</option>
            <option value="numerasi">Numerasi</option>
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tema / Judul Bacaan <span
            class="text-red-500">*</span></label>
    <input type="text" name="tema" id="{{ $prefix }}tema" required
        placeholder="Contoh: Manfaat hutan mangrove bagi pesisir"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">Soal Kesimpulan <span
            class="text-red-500">*</span></label>
    <textarea name="soal" id="{{ $prefix }}soal" rows="3" required
        placeholder="Buatlah kesimpulan dari bacaan di atas..."
        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal & Waktu Mulai <span
                class="text-red-500">*</span></label>
        <input type="datetime-local" name="waktu_mulai" id="{{ $prefix }}waktu_mulai" required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi Pengerjaan (menit) <span
                class="text-red-500">*</span></label>
        <input type="number" name="durasi_menit" id="{{ $prefix }}durasi_menit" required min="1" placeholder="60"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">
        Upload Bacaan (PDF) {{ ($edit ?? false) ? '— kosongkan jika tidak diganti' : '' }}
        @if(!($edit ?? false))<span class="text-red-500">*</span>@endif
    </label>
    <input type="file" name="file_pdf" accept="application/pdf" {{ ($edit ?? false) ? '' : 'required' }}
        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    <p class="text-xs text-gray-500 mt-1">PDF akan dikonversi otomatis menjadi gambar agar tidak bisa disalin oleh
        siswa.</p>
</div>