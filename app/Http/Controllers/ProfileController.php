<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function showEditProfile()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get();
        $completedOrders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->where('order_status', 'selesai')
            ->orderBy('completed_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('edit-profile', compact('user', 'completedOrders', 'addresses'));
    }
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'addresses' => 'required|json'
        ]);

        DB::transaction(function () use ($user, $validated, $request) {
            // Update user basic info
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'] ?? null,
            ]);

            // Handle addresses
            $addresses = json_decode($validated['addresses'], true);

            // Delete existing addresses
            $user->addresses()->delete();

            // Ensure at least one address is primary
            $hasPrimary = collect($addresses)->contains('is_primary', true);
            if (!$hasPrimary && count($addresses) > 0) {
                $addresses[0]['is_primary'] = true;
            }

            // Create new addresses
            foreach ($addresses as $addressData) {
                if (!empty($addressData['full_address'])) {
                    $user->addresses()->create([
                        'label' => $addressData['label'] ?: 'Alamat',
                        'recipient_name' => $addressData['recipient_name'] ?: $user->name,
                        'phone' => $addressData['phone'] ?: $user->phone,
                        'full_address' => $addressData['full_address'],
                        'city' => $addressData['city'],
                        'state' => $addressData['state'],
                        'postal_code' => $addressData['postal_code'],
                        'is_primary' => $addressData['is_primary'] ?? false
                    ]);
                }
            }
        });

        return redirect()->route('profile.edit')->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Delete specific address
     */
    public function deleteAddress(Address $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Don't allow deleting if it's the only address
        if ($user->addresses()->count() <= 1) {
            return response()->json(['error' => 'Tidak dapat menghapus alamat terakhir'], 400);
        }

        // If deleting primary address, set another as primary
        if ($address->is_primary) {
            $newPrimary = $user->addresses()->where('id', '!=', $address->id)->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        $address->delete();

        return response()->json(['success' => 'Alamat berhasil dihapus']);
    }

    /**
     * Set address as primary
     */
    public function setPrimaryAddress(Address $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $address->setPrimary();

        return response()->json(['success' => 'Alamat utama berhasil diatur']);
    }
}