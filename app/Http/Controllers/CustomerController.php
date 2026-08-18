<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'installments' => function($q) {
                $q->orderBy('installment_number', 'asc');
            }])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function settings()
    {
        return redirect()->route('customer.orders');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        switch ($request->input('_action')) {
            case 'update_profile_info':
                $request->validate([
                    'name' => 'required|string|max:255',
                    'phone' => 'required|string|max=20',
                    'address' => 'required|string',
                ]);

                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->address = $request->address;
                $user->save();

                return redirect()->route('customer.orders')->with('success', 'Informasi profil berhasil diperbarui.');

            case 'update_password':
                $request->validate([
                    'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                        if (!\Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                            $fail('Kata sandi saat ini tidak cocok.');
                        }
                    }],
                    'password' => 'required|string|min:8|confirmed',
                ]);

                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                $user->save();

                return redirect()->route('customer.orders')->with('success', 'Kata sandi berhasil diperbarui.');

            case 'update_profile_photo':
                $request->validate([
                    'profile_photo' => 'required|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Delete old profile photo if exists
                if ($user->profile_photo_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
                }

                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
                $user->save();

                return redirect()->route('customer.orders')->with('success', 'Foto profil berhasil diunggah.');

            default:
                return redirect()->route('customer.orders')->with('error', 'Tidak ada perubahan yang terdeteksi.');
        }
    }

    public function uploadCashProof(Request $request, Order $order)
    {
        $this->authorize('uploadCashProof', $order);

        $request->validate([
            'payment_proof' => 'required|image|max:3072',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update([
                'payment_proof_path' => $path,
                'payment_status' => 'pending_verification',
                'status' => 'verifying_payment'
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi oleh Admin.');
    }
}
