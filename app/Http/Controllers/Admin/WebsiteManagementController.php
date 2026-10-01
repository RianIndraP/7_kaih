<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteManagement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WebsiteManagementController extends Controller
{
    public function index(): View
    {
        $settings = WebsiteManagement::getSettings();
        
        return view('admin.manajemen-website', compact('settings'));
    }

    /**
     * Bersihkan HTML dari konten rich-text Quill.
     *
     * Quill menghasilkan HTML saat disimpan, tapi validasi server hanya
     *memeriksa tipe string — sehingga <script> dan onerror= bisa tersimpan lalu
     * dieksekusi di halaman publik website-locked.blade.php.
     * Hanya tag formatting Quill yang dipertahankan.
     */
    private function sanitizeRichText(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        $allowed = '<p><br><b><strong><i><em><u><s><a><ul><ol><li>'
            . '<h1><h2><h3><h4><blockquote><pre><code><span><div>';

        // Buang event handler inline: onclick= onerror= onload= dll
        $html = preg_replace('/\son[a-z]+\s*=\s*(["\']).*?\1/i', '', $html);
        $html = preg_replace('/\son[a-z]+\s*=\s*[^\s>]+/i', '', $html);

        // Buang <script> dan <style> beserta isinya
        $html = preg_replace('#<\s*(script|style|iframe|object|embed)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html);
        $html = preg_replace('#<\s*(script|style|iframe|object|embed)\b[^>]*/?\s*>#is', '', $html);

        // Buang javascript: / data: pada href & src
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*data:text\/html[^"\']*\2/i', '$1=$2#$2', $html);

        return strip_tags($html, $allowed);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_locked' => 'nullable|boolean',
            'lock_message' => 'nullable|string|max:5000',
            'update_message_expiry_date' => 'nullable|date',
            'update_message_siswa' => 'nullable|string|max:5000',
            'update_message_guru' => 'nullable|string|max:5000',
            'update_message_kepala_sekolah' => 'nullable|string|max:5000',
        ]);

        // Set default value for is_locked if not provided (checkbox unchecked)
        $validated['is_locked'] = $request->has('is_locked') ? true : false;

        foreach (['lock_message', 'update_message_siswa', 'update_message_guru', 'update_message_kepala_sekolah'] as $field) {
            if (array_key_exists($field, $validated)) {
                $validated[$field] = $this->sanitizeRichText($validated[$field]);
            }
        }

        $settings = WebsiteManagement::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.manajemen-website')
            ->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
