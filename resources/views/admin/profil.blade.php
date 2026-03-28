@extends('layouts.admin')

@section('title', 'Profil Operator | SMK N 5 Telkom Banda Aceh')
@section('page_title', 'Profil Operator')

@section('content')

<div class="w-full">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Profil Operator</h1>
        <button type="submit" form="formProfil" id="btnSave"
                class="hidden px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Profil --}}
    <form id="formProfil" action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-start gap-8">
                {{-- Foto Profil --}}
                <div class="shrink-0 text-center">
                    <div class="relative w-28 h-36 bg-gray-100 border-2 border-gray-200 rounded-xl overflow-hidden group cursor-pointer"
                         onclick="document.getElementById('inputFoto').click()">
                        @if (!empty($user->foto))
                            <img id="previewFoto" src="{{ asset('storage/' . $user->foto) }}"
                                 alt="Foto {{ $user->name }}"
                                 class="w-full h-full object-cover"/>
                        @else
                            <div id="previewFoto" class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Overlay upload --}}
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0
                                         011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2
                                         2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="file" id="inputFoto" name="foto" accept="image/*" class="hidden" onchange="handleFotoChange(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Klik foto untuk ganti</p>
                </div>

                {{-- Info Data --}}
                <div class="flex-1 grid grid-cols-3 gap-x-6 gap-y-4">
                    {{-- Nama --}}
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" data-original="{{ $user->name }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               oninput="checkChanges()">
                    </div>

                    {{-- NIP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                        <input type="text" name="nip" value="{{ $user->nip }}" data-original="{{ $user->nip }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               oninput="checkChanges()">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" data-original="{{ $user->email }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               oninput="checkChanges()">
                    </div>

                    {{-- No. HP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                        <input type="tel" name="no_telepon" value="{{ $user->no_telepon }}" data-original="{{ $user->no_telepon }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               oninput="checkChanges()">
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ $user->tempat_lahir }}" data-original="{{ $user->tempat_lahir }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               oninput="checkChanges()">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $user->birth_date?->format('Y-m-d') }}" data-original="{{ $user->birth_date?->format('Y-m-d') }}"
                               class="editable-field w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300 transition-colors"
                               onchange="checkChanges()">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    const btnSave = document.getElementById('btnSave');
    let fotoChanged = false;

    function checkChanges() {
        const fields = document.querySelectorAll('.editable-field');
        let hasChanges = fotoChanged;

        fields.forEach(field => {
            const original = field.getAttribute('data-original') || '';
            const current = field.value || '';
            if (original !== current) {
                hasChanges = true;
            }
        });

        // Show/hide save button
        if (hasChanges) {
            btnSave.classList.remove('hidden');
            btnSave.classList.add('flex');
        } else {
            btnSave.classList.add('hidden');
            btnSave.classList.remove('flex');
        }
    }

    function handleFotoChange(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            const previewFoto = document.getElementById('previewFoto');
            reader.onload = (e) => {
                if (previewFoto.tagName === 'IMG') {
                    previewFoto.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';
                    previewFoto.innerHTML = '';
                    previewFoto.appendChild(img);
                }
            };
            reader.readAsDataURL(file);
            fotoChanged = true;
            checkChanges();
        }
    }
</script>
@endsection

