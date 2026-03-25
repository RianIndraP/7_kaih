<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKebiasaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section'  => ['required', 'string', 'in:bangun_pagi,beribadah,berolahraga,makan_sehat,gemar_belajar,bermasyarakat,tidur_cepat'],
            'tanggal'  => ['nullable', 'date', 'before_or_equal:today'],

            // ── Bangun Pagi ─────────────────────────────────────────────
            'status'   => ['nullable', 'string', 'in:iya,tidak'],
            'jam'      => ['nullable', 'string'],   // H:i
            'catatan'  => ['nullable', 'string', 'max:2000'],

            // ── Beribadah ───────────────────────────────────────────────
            // sholat dikirim sebagai object {subuh:1, dzuhur:0, ...} → array
            'sholat'           => ['nullable', 'array'],
            'sholat.subuh'     => ['nullable'],
            'sholat.dzuhur'    => ['nullable'],
            'sholat.ashar'     => ['nullable'],
            'sholat.maghrib'   => ['nullable'],
            'sholat.isya'      => ['nullable'],
            // jam per sholat
            'jam_subuh'        => ['nullable', 'string'],
            'jam_dzuhur'       => ['nullable', 'string'],
            'jam_ashar'        => ['nullable', 'string'],
            'jam_maghrib'      => ['nullable', 'string'],
            'jam_isya'         => ['nullable', 'string'],
            'quran'            => ['nullable', 'string', 'in:iya,tidak'],
            'surah'            => ['nullable'],

            // ── Berolahraga ─────────────────────────────────────────────
            // jenis dikirim sebagai [{jenis:'...', catatan:'...'}]
            'jenis'            => ['nullable', 'array'],
            'jenis.*.jenis'    => ['nullable', 'string', 'max:100'],
            'jenis.*.catatan'  => ['nullable', 'string', 'max:1000'],

            // ── Makan Sehat ─────────────────────────────────────────────
            'pagi'       => ['nullable', 'string', 'max:255'],
            'pagi_done'  => ['nullable'],
            'siang'      => ['nullable', 'string', 'max:255'],
            'siang_done' => ['nullable'],
            'malam'      => ['nullable', 'string', 'max:255'],
            'malam_done' => ['nullable'],

            // ── Gemar Belajar ───────────────────────────────────────────
            'pelajaran'  => ['nullable', 'string', 'max:255'],

            // ── Bermasyarakat ───────────────────────────────────────────
            'dengan'     => ['nullable', 'array'],
            'dengan.*'   => ['nullable', 'string', 'in:keluarga,teman,tetangga,publik'],
        ];
    }

    public function messages(): array
    {
        return [
            'section.required' => 'Section kebiasaan wajib diisi.',
            'section.in'       => 'Section kebiasaan tidak valid.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
        ];
    }
}