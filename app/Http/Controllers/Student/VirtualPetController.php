<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\VirtualPet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VirtualPetController extends Controller
{
    /**
     * Change pet form (appearance)
     */
    public function changeForm(Request $request)
    {
        $request->validate([
            'form' => 'required|string|in:egg,baby,child,teen,adult,legendary'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pet = $user->getOrCreateVirtualPet();

        // Check if form is unlocked
        $availableForms = $pet->getAvailableForms();
        
        if (!isset($availableForms[$request->form])) {
            return response()->json([
                'success' => false,
                'message' => 'Form belum terbuka!'
            ], 403);
        }

        // Update pet form
        $pet->form = $request->form;
        $pet->save();

        return response()->json([
            'success' => true,
            'message' => 'Bentuk karakter berhasil diubah!',
            'form' => $request->form
        ]);
    }

    /**
     * Get pet status for API
     */
    public function getStatus()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pet = $user->getOrCreateVirtualPet();

        return response()->json([
            'success' => true,
            'pet' => [
                'name' => $pet->name,
                'level' => $pet->level,
                'form' => $pet->form,
                'form_details' => $pet->getFormDetails(),
                'health' => $pet->health,
                'happiness' => $pet->happiness,
                'is_alive' => $pet->is_alive,
                'emoji' => $pet->getCurrentEmoji(),
                'unlocked_forms' => $pet->unlocked_forms,
                'available_forms' => $pet->getAvailableForms()
            ]
        ]);
    }

    /**
     * Rename pet
     */
    public function rename(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pet = $user->getOrCreateVirtualPet();

        $pet->name = $request->name;
        $pet->save();

        return response()->json([
            'success' => true,
            'message' => 'Nama karakter berhasil diubah!',
            'name' => $pet->name
        ]);
    }
}
