<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Perusahaan;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Replicating logic from 'after_user_insert' trigger for 'desa' identity
        if ($user->identity_type === 'desa') {
            // Update reg_villages status_berlistrik to 'strip'
            if ($user->village_id) {
                \App\Models\RegVillage::where('id', $user->village_id)
                    ->update(['status_berlistrik' => 'strip']);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Check if user is a company type and doesn't have a linked company yet
        if ($user->identity_type === 'perusahaan' && is_null($user->perusahaan_id)) {
            
            // Check if company name allows creation (should be set during registration)
            if (!empty($user->company_name)) {
                
                // Check if BOTH email is verified AND admin has approved
                if ($user->hasVerifiedEmail() && $user->is_verified) {
                    
                    // Get Regency name if possible
                    $kabupatenKota = null;
                    try {
                        // Assuming village -> district -> regency relationship exists and is loaded or accessible
                        if ($user->village && $user->village->district && $user->village->district->regency) {
                            $kabupatenKota = $user->village->district->regency->name;
                        }
                    } catch (\Exception $e) {
                        // Ignore error if relation fails, leave kabupaten_kota null or default
                    }

                    // Create the Company
                    $perusahaan = Perusahaan::create([
                        'nama' => $user->company_name,
                        'nama_pimpinan' => $user->name, // Using user name as initial pimpinan
                        'alamat' => $user->address,
                        'village_id' => $user->village_id,
                        'kontak' => $user->phone,
                        'jenis_usaha' => 'Lainnya', // Default or need to add field in registration?
                        'kabupaten_kota' => $kabupatenKota,
                    ]);

                    // Link the new Company to the User
                    // We use quiet update to avoid infinite loops if this observer triggers on update again
                    // Although checking is_null($perusahaan_id) prevents the loop logic-wise.
                    $user->perusahaan_id = $perusahaan->id;
                    $user->saveQuietly(); 
                }
            }
        }
    }
}
