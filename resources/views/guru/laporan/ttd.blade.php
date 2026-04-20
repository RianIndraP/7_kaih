<div class="mt-8 flex justify-between items-start px-10">
    <div class="text-center text-sm text-gray-700">
        <div>Mengetahui,</div>
        <div class="font-semibold mt-0.5">Kepala Sekolah</div>
        <div class="mt-20">
            <div class="font-bold border-b border-gray-700 inline-block px-1">
                Anas, S.Ag.,MA
            </div>
            <div class="text-xs text-gray-500 mt-1">NIP. 197512122002121006</div>
        </div>
    </div>
    <div class="text-center text-sm text-gray-700">
        <div>Banda Aceh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        <div class="font-semibold mt-0.5">Guru Wali Kelas</div>
        <div class="mt-20">
            <div class="font-bold border-b border-gray-700 inline-block px-1">
                {{ $user->name ?? '____________________' }}
            </div>
            <div class="text-xs text-gray-500 mt-1">NIP. {{ $user->nip ?? '____________________' }}</div>
        </div>
    </div>
</div>
