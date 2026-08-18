<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serve($path)
    {
        // Decode the path
        $decodedPath = base64_decode($path);

        // Find the order or installment associated with this file
        $orderAsKtp = Order::where('ktp_path', $decodedPath)->first();
        $orderAsPaymentProof = Order::where('payment_proof_path', $decodedPath)->first();
        $installmentAsPaymentProof = Installment::where('payment_proof', $decodedPath)->first();

        $resource = $orderAsKtp ?? $orderAsPaymentProof ?? ($installmentAsPaymentProof ? $installmentAsPaymentProof->order : null);

        // If no resource is found, or user is not authenticated, deny access
        if (!$resource || !Auth::check()) {
            abort(404);
        }

        $user = Auth::user();

        // Check if the user is an admin/owner or the owner of the order
        if ($user->isAdmin() || $user->isOwner() || $user->id === $resource->user_id) {
            if (Storage::disk('private_uploads')->exists($decodedPath)) {
                return Storage::disk('private_uploads')->response($decodedPath);
            }
        }

        abort(403, 'AKSES DITOLAK.');
    }
}
