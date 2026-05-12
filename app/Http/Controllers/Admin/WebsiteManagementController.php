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

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_locked' => 'nullable|boolean',
            'lock_message' => 'nullable|string',
            'update_message_expiry_date' => 'nullable|date',
            'update_message_siswa' => 'nullable|string',
            'update_message_guru' => 'nullable|string',
            'update_message_kepala_sekolah' => 'nullable|string',
        ]);

        // Set default value for is_locked if not provided (checkbox unchecked)
        $validated['is_locked'] = $request->has('is_locked') ? true : false;

        $settings = WebsiteManagement::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.manajemen-website')
            ->with('success', 'Pengaturan website berhasil diperbarui!');
    }
}
