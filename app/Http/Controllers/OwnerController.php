<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Installment;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\WebSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard(Request $request)
    {
        $settings = WebSetting::getSettings();

        // 1. Revenue & Income Analytics
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $cashRevenue = Order::where('payment_method', 'cash')
            ->where('shipping_address', '!=', 'Toko Offline')
            ->where(function ($query) {
                $query->where('payment_status', 'verified')
                      ->orWhere('status', 'completed');
            })
            ->sum('total_amount');
        $offlineRevenue = Order::where('shipping_address', 'Toko Offline')->where('status', '!=', 'cancelled')->sum('total_amount');
        $creditRevenue = Order::where('payment_method', 'credit')->where('status', '!=', 'cancelled')->sum('total_amount');

        // Total Received Cash
        $downPaymentsPaid = Installment::where('installment_number', 0)->where('status', 'paid')->sum('amount');
        $installmentsPaid = Installment::where('installment_number', '>', 0)->where('status', 'paid')->sum('amount');
        $totalReceivedIncome = $cashRevenue + $offlineRevenue + $downPaymentsPaid + $installmentsPaid;

        // Total Outstanding Piutang Kredit
        $outstandingCredit = Installment::where('status', '!=', 'paid')->sum('amount');

        // 2. Stock Monitoring
        $totalBarangMasuk = StockMovement::where('type', 'in')->sum('quantity');
        $totalBarangKeluar = StockMovement::where('type', 'out')->sum('quantity');

        $movementQuery = StockMovement::with(['product.category', 'user']);
        if ($request->has('type') && in_array($request->type, ['in', 'out'])) {
            $movementQuery->where('type', $request->type);
        }
        $stockMovements = $movementQuery->latest()->paginate(10, ['*'], 'stock_page');

        // Products Stock Overview
        $products = Product::with('category')->latest()->paginate(10, ['*'], 'products_page');
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        // 3. Orders with Pagination
        $cashOrders = Order::with(['user', 'items.product'])
            ->where('payment_method', 'cash')
            ->where('shipping_address', '!=', 'Toko Offline')
            ->latest()
            ->paginate(10, ['*'], 'cash_page');

        $offlineOrders = Order::with(['user', 'items.product'])
            ->where('shipping_address', 'Toko Offline')
            ->latest()
            ->paginate(10, ['*'], 'offline_page');

        $creditOrders = Order::with(['user', 'items.product', 'installments' => function($q) {
                $q->orderBy('installment_number', 'asc');
            }])
            ->where('payment_method', 'credit')
            ->latest()
            ->paginate(10, ['*'], 'credit_page');

        $totalCashOrders = Order::where('payment_method', 'cash')->where('shipping_address', '!=', 'Toko Offline')->count();
        $totalOfflineOrders = Order::where('shipping_address', 'Toko Offline')->count();
        $totalCreditOrders = Order::where('payment_method', 'credit')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        // 4. Analytics Data
        $weeklyLabels = [];
        $weeklyCashData = [];
        $weeklyOfflineData = [];
        $weeklyCreditData = [];
        $weeklyTotalData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d M');

            $dayCash = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');

            $dayOffline = Order::where('shipping_address', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');

            $dayCredit = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');

            $weeklyCashData[] = $dayCash;
            $weeklyOfflineData[] = $dayOffline;
            $weeklyCreditData[] = $dayCredit;
            $weeklyTotalData[] = $dayCash + $dayOffline + $dayCredit;
        }

        $monthlyLabels = [];
        $monthlyCashData = [];
        $monthlyOfflineData = [];
        $monthlyCreditData = [];
        $monthlyTotalData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->translatedFormat('M Y');

            $monthCash = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $monthOffline = Order::where('shipping_address', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $monthCredit = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $monthlyCashData[] = $monthCash;
            $monthlyOfflineData[] = $monthOffline;
            $monthlyCreditData[] = $monthCredit;
            $monthlyTotalData[] = $monthCash + $monthOffline + $monthCredit;
        }

        $yearlyLabels = [];
        $yearlyCashData = [];
        $yearlyOfflineData = [];
        $yearlyCreditData = [];
        $yearlyTotalData = [];

        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $yearlyLabels[] = (string) $year;

            $yearCash = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->sum('total_amount');

            $yearOffline = Order::where('shipping_address', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->sum('total_amount');

            $yearCredit = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->sum('total_amount');

            $yearlyCashData[] = $yearCash;
            $yearlyOfflineData[] = $yearOffline;
            $yearlyCreditData[] = $yearCredit;
            $yearlyTotalData[] = $yearCash + $yearOffline + $yearCredit;
        }

        return view('owner.dashboard', compact(
            'settings',
            'totalRevenue',
            'cashRevenue',
            'offlineRevenue',
            'creditRevenue',
            'totalReceivedIncome',
            'outstandingCredit',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'stockMovements',
            'products',
            'cashOrders',
            'offlineOrders',
            'creditOrders',
            'totalProducts',
            'pendingOrders',
            'outOfStockCount',
            'totalCashOrders',
            'totalOfflineOrders',
            'totalCreditOrders',
            'completedOrders',
            'weeklyLabels',
            'weeklyCashData',
            'weeklyOfflineData',
            'weeklyCreditData',
            'weeklyTotalData',
            'monthlyLabels',
            'monthlyCashData',
            'monthlyOfflineData',
            'monthlyCreditData',
            'monthlyTotalData',
            'yearlyLabels',
            'yearlyCashData',
            'yearlyOfflineData',
            'yearlyCreditData',
            'yearlyTotalData'
        ));
    }

    public function exportExcel()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        $settings = WebSetting::getSettings();
        $siteNameSlug = Str::slug($settings->site_name, '_');
        $filename = 'Laporan_Penjualan_' . $siteNameSlug . '_' . date('Y-m-d_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No Pesanan', 'Tanggal', 'Pelanggan', 'No HP', 'Metode Bayar', 'Tenor (Bulan)', 'Subtotal (Rp)', 'Ongkir (Rp)', 'Total Tagihan (Rp)', 'Status'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $subtotal = $order->total_amount - ($order->shipping_cost ?: 0);
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->customer_name ?: ($order->user->name ?? '-'),
                    $order->customer_phone ?: ($order->user->phone ?? '-'),
                    strtoupper($order->payment_method),
                    $order->credit_tenor_months ?: '-',
                    $subtotal,
                    $order->shipping_cost ?: 0,
                    $order->total_amount,
                    strtoupper($order->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $settings = WebSetting::getSettings();
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::count();
        $totalStock = Product::sum('stock');

        return view('owner.report_pdf', compact('settings', 'orders', 'totalRevenue', 'totalOrders', 'totalStock'));
    }

    public function settings()
    {
        return redirect()->route('owner.dashboard');
    }

    public function updateSettings(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Handle Profile Information Update
        if ($request->has(['name', 'phone', 'address']) && !$request->has('current_password') && !$request->hasFile('profile_photo')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
            ]);

            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            return redirect()->route('owner.settings')->with('success', 'Informasi profil Anda berhasil diperbarui.');
        }

        // Handle Password Update
        if ($request->has(['current_password', 'password', 'password_confirmation'])) {
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

            return redirect()->route('owner.settings')->with('success', 'Kata sandi Anda berhasil diperbarui.');
        }

        // Handle Profile Photo Update
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'required|image|max:2048',
            ]);

            // Delete old profile photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();

            return redirect()->route('owner.settings')->with('success', 'Foto profil Anda berhasil diunggah.');
        }

        return redirect()->route('owner.settings')->with('error', 'Tidak ada perubahan yang terdeteksi.');
    }

    public function updateAdminSettings(Request $request)
    {
        $admin = \App\Models\User::where('role', 'admin')->firstOrFail();

        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('owner.settings')->with('success', 'Pengaturan akun Admin berhasil diperbarui.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|in:pending,approved,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diperbarui!');
    }

    public function updateInstallmentStatus(Request $request, Installment $installment)
    {
        $this->authorize('update', $installment);

        $request->validate([
            'status' => 'required|in:unpaid,paid,overdue',
        ]);

        $installment->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]);

        $order = $installment->order;
        if ($order) {
            $dpInstallment = $order->installments()->where('installment_number', 0)->first();
            $isDpPaid = $dpInstallment && $dpInstallment->status === 'paid';

            $totalInstallments = $order->installments()->count();
            $paidInstallments = $order->installments()->where('status', 'paid')->count();

            if ($totalInstallments > 0 && $paidInstallments === $totalInstallments) {
                $order->update(['status' => 'completed']);
            } elseif ($isDpPaid) {
                $order->update(['status' => 'approved']);
            } else {
                $order->update(['status' => 'pending']);
            }
        }

        return redirect()->back()->with('success', 'Status angsuran cicilan berhasil diperbarui!');
    }
}
