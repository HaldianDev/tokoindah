<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreditRequest;
use App\Models\Installment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification; // Import Notification facade
use App\Notifications\PaymentVerificationNotification; // Import the notification class

class AdminController extends Controller
{
    public function approvePayment(Order $order)
    {
        Gate::authorize('update', $order);
        
        if ($order->payment_method !== 'cash' || $order->status !== 'verifying_payment') {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        $order->update([
            'status' => \App\Models\Order::STATUS_COMPLETED,
            'payment_status' => 'verified',
        ]);

        // Notify owner
        $ownerEmail = WebSetting::getSettings()->email;
        Notification::route('mail', $ownerEmail)->notify(new PaymentVerificationNotification($order, 'approved'));

        return redirect()->back()->with('success', 'Pembayaran untuk pesanan #' . $order->order_number . ' telah disetujui.');
    }

    public function rejectPayment(Order $order)
    {
        Gate::authorize('update', $order);

        if ($order->payment_method !== 'cash' || $order->status !== 'verifying_payment') {
            return redirect()->back()->with('error', 'Aksi tidak valid.');
        }

        // Delete the old proof
        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $order->update([
            'status' => 'waiting_payment',
            'payment_status' => 'rejected',
            'payment_proof_path' => null,
        ]);

        // Notify owner
        $ownerEmail = WebSetting::getSettings()->email;
        Notification::route('mail', $ownerEmail)->notify(new PaymentVerificationNotification($order, 'rejected'));

        return redirect()->back()->with('error', 'Pembayaran untuk pesanan #' . $order->order_number . ' ditolak. Pelanggan dapat mengupload ulang bukti.');
    }
    
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        $product->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyCategory(Category $category)
    {
        Gate::authorize('delete', $category);
        $category->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Kategori berhasil dihapus.');
    }


    public function dashboard(Request $request)
    {
        $settings = WebSetting::getSettings();

        // 1. Overview counts
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        // 2. Paginated Products
        $products = Product::with('category')->latest()->paginate(10, ['*'], 'products_page');
        $cashierProducts = Product::with('category')->latest()->get();
        $categoriesList = Category::withCount('products')->latest()->paginate(10, ['*'], 'categories_page');
        $allCategories = Category::all();
        $categories = $allCategories; // Alias for cashier filter dropdown

        // 3. Orders with Pagination
        $cashOrders = Order::with(['user', 'items.product'])
            ->where('payment_method', 'cash')
            ->where('shipping_address', '!=', 'Toko Offline')
            ->orderByRaw("CASE WHEN status = 'verifying_payment' THEN 0 ELSE 1 END")
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

        // Recent orders for installment modal data (credit orders only)
        $recentOrders = Order::with(['items.product', 'installments' => function($q) {
                $q->orderBy('installment_number', 'asc');
            }])
            ->where('payment_method', 'credit')
            ->latest()
            ->limit(50)
            ->get();

        $stockMovements = StockMovement::with(['product', 'user'])->latest()->paginate(15, ['*'], 'stock_page');

        // Total order counts
        $totalCashOrders = Order::where('payment_method', 'cash')->where('shipping_address', '!=', 'Toko Offline')->count();
        $totalOfflineOrders = Order::where('shipping_address', 'Toko Offline')->count();
        $totalCreditOrders = Order::where('payment_method', 'credit')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        // 4. Analytics Data
        $weeklyLabels = [];
        $weeklyCashData = [];
        $weeklyCreditData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D, d M');

            $weeklyCashData[] = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');

            $weeklyCreditData[] = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');
        }

        $monthlyLabels = [];
        $monthlyCashData = [];
        $monthlyCreditData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->translatedFormat('M Y');

            $monthlyCashData[] = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $monthlyCreditData[] = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');
        }

        $yearlyLabels = [];
        $yearlyCashData = [];
        $yearlyCreditData = [];

        for ($i = 4; $i >= 0; $i--) {
            $year = \Carbon\Carbon::now()->subYears($i)->year;
            $yearlyLabels[] = (string) $year;

            $yearlyCashData[] = Order::where('payment_method', 'cash')
                ->where('shipping_address', '!=', 'Toko Offline')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->sum('total_amount');

            $yearlyCreditData[] = Order::where('payment_method', 'credit')
                ->where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->sum('total_amount');
        }

        // For Add Stock Modal
        $productsGrouped = Product::with('category')->orderBy('name')->get()->groupBy('category.name');

        return view('admin.dashboard', compact(
            'settings',
            'totalProducts',
            'totalCategories',
            'pendingOrders',
            'outOfStockCount',
            'products',
            'cashierProducts',
            'categoriesList',
            'allCategories',
            'categories',
            'recentOrders',
            'stockMovements',
            'cashOrders',
            'offlineOrders',
            'creditOrders',
            'totalCashOrders',
            'totalOfflineOrders',
            'totalCreditOrders',
            'completedOrders',
            'weeklyLabels',
            'weeklyCashData',
            'weeklyCreditData',
            'monthlyLabels',
            'monthlyCashData',
            'monthlyCreditData',
            'yearlyLabels',
            'yearlyCashData',
            'yearlyCreditData',
            'productsGrouped'
        ));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'weight'      => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image_file'  => 'nullable|image|max:3072',
            'image_url'   => 'nullable|string',
            'spec_1'      => 'nullable|string',
            'spec_2'      => 'nullable|string',
            'spec_3'      => 'nullable|string',
        ]);

        $imagePath = 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=500&q=80';

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif (!empty($request->image_url)) {
            $imagePath = $request->image_url;
        }

        $status = $request->stock > 0 ? 'ready' : 'out_of_stock';

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'weight'      => $request->weight,
            'image'       => $imagePath,
            'spec_1'      => $request->spec_1,
            'spec_2'      => $request->spec_2,
            'spec_3'      => $request->spec_3,
            'status'      => $status,
        ]);

        // Log initial stock movement if stock > 0
        if ($request->stock > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'quantity'   => $request->stock,
                'notes'      => 'Stok awal produk baru',
                'user_id'    => Auth::id(),
            ]);
        }

        return redirect()->back()->with('success', 'Produk keramik baru berhasil ditambahkan!');
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('stock', $request->quantity);
        $product->update(['status' => 'ready']);

        // Log Barang Masuk for Owner Monitoring
        StockMovement::create([
            'product_id' => $product->id,
            'type'       => 'in',
            'quantity'   => $request->quantity,
            'notes'      => $request->notes ?? 'Penambahan stok manual oleh Admin',
            'user_id'    => Auth::id(),
        ]);

        return redirect()->back()->with('success', "Stok {$product->name} berhasil ditambah {$request->quantity} unit!");
    }

    public function editProduct(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::all();
        return response()->json([
            'product' => $product->load('category'), // Load category to get category name
            'categories' => $categories
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'weight'      => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image_file'  => 'nullable|image|max:3072',
            'image_url'   => 'nullable|string',
            'spec_1'      => 'nullable|string',
            'spec_2'      => 'nullable|string',
            'spec_3'      => 'nullable|string',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        } elseif (!empty($request->image_url)) {
            $imagePath = $request->image_url;
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'weight'      => $request->weight,
            'image'       => $imagePath,
            'spec_1'      => $request->spec_1,
            'spec_2'      => $request->spec_2,
            'spec_3'      => $request->spec_3,
            'status'      => $request->stock > 0 ? 'ready' : 'out_of_stock',
        ]);

        return response()->json(['success' => true, 'message' => "Produk '{$product->name}' berhasil diperbarui!"]);
    }

    // ====== CATEGORY CRUD ======
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'icon' => 'nullable|string|max:100',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?: 'fa-solid fa-layer-group',
        ]);

        return redirect()->back()->with('success', "Kategori '{$request->name}' berhasil dibuat!");
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?: $category->icon,
        ]);

        return redirect()->back()->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    // ====== WEB SETTINGS ======
    public function updateWebSettings(Request $request)
    {
        $request->validate([
            'site_name'            => 'required|string|max:255',
            'whatsapp_number'      => 'required|string|max:50',
            'phone'                => 'nullable|string|max:50',
            'email'                => 'nullable|email|max:100',
            'store_address'        => 'nullable|string',
            'shipping_cost_per_kg' => 'required|integer|min:0',
            'hero_title'           => 'nullable|string|max:255',
            'hero_subtitle'        => 'nullable|string',
            'about_title'          => 'nullable|string|max:255',
            'about_description'    => 'nullable|string',
            'about_vision'         => 'nullable|string',
            'about_mission'        => 'nullable|string',
            'logo_file'            => 'nullable|mimes:jpeg,png,jpg|max:1024',
            'about_file'           => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ]);

        $settings = WebSetting::getSettings();
        $data = $request->except(['logo_file', 'about_file', '_token']);

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('settings', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        if ($request->hasFile('about_file')) {
            $path = $request->file('about_file')->store('settings', 'public');
            $data['about_image'] = '/storage/' . $path;
        }

        $settings->update($data);

        return redirect()->back()->with('success', 'Pengaturan website berhasil disimpan!');
    }

    public function editWebSettings()
    {
        $settings = WebSetting::getSettings();
        return view('admin.web_settings', compact('settings'));
    }

    public function settings()
    {
        return redirect()->route('admin.dashboard');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

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

            return redirect()->route('admin.settings')->with('success', 'Informasi profil berhasil diperbarui.');
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

            return redirect()->route('admin.settings')->with('success', 'Kata sandi berhasil diperbarui.');
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

            return redirect()->route('admin.settings')->with('success', 'Foto profil berhasil diunggah.');
        }

        return redirect()->route('admin.settings')->with('error', 'Tidak ada perubahan yang terdeteksi.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => 'required|in:pending,approved,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', "Status pesanan #{$order->order_number} berhasil diperbarui!");
    }

    public function cashierCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'cash_received' => 'nullable|numeric|min:0',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($request->cart_items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                if ($product->stock < $itemData['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok produk '{$product->name}' tidak mencukupi. (Stok tersedia: {$product->stock})",
                    ], 422);
                }

                $subtotal = $product->price * $itemData['quantity'];
                $totalAmount += $subtotal;

                $itemsToCreate[] = [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $customerName = $request->customer_name ?: 'Pelanggan Umum';
            $customerPhone = $request->customer_phone ?: '-';
            $cashReceived = $request->cash_received ?: $totalAmount;
            $changeAmount = max(0, $cashReceived - $totalAmount);

            $order = Order::create([
                'order_number'        => 'RK-POS-' . strtoupper(Str::random(6)),
                'user_id'             => Auth::id(),
                'payment_method'      => 'cash',
                'credit_tenor_months' => null,
                'down_payment'        => 0,
                'monthly_installment' => 0,
                'total_amount'        => $totalAmount,
                'shipping_cost'       => 0,
                'total_weight'        => 0,
                'status'              => 'completed',
                'customer_name'       => $customerName,
                'customer_phone'      => $customerPhone,
                'shipping_address'    => 'Toko Offline',
                'notes'               => 'Transaksi Kasir POS Offline',
                'is_offline'          => true,
            ]);

            foreach ($itemsToCreate as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                // Update product stock
                $product = $item['product'];
                $product->decrement('stock', $item['quantity']);
                if ($product->stock <= 0) {
                    $product->update(['status' => 'out_of_stock']);
                }

                // Log Stock Movement Out
                StockMovement::create([
                    'product_id' => $product->id,
                    'type'       => 'out',
                    'quantity'   => $item['quantity'],
                    'notes'      => 'Penjualan Offline Kasir #' . $order->order_number,
                    'user_id'    => Auth::id(),
                ]);
            }

            DB::commit();

            $order->load('items.product');

            return response()->json([
                'success'       => true,
                'message'       => 'Transaksi kasir offline berhasil diproses!',
                'order'         => $order,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function forwardCreditRequest(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->payment_method !== 'credit') {
            return redirect()->back()->with('error', 'Order bukan permintaan kredit.');
        }
        CreditRequest::create([
            'order_id' => $order->id,
            'admin_id' => Auth::id(),
            'owner_id' => $order->user_id,
            'status'   => 'pending',
        ]);
        return redirect()->back()->with('success', 'Permintaan kredit berhasil diteruskan ke owner.');
    }

    public function getProductsByCategory(Request $request, $id)
    {
        $products = Product::where('category_id', $id)->orderBy('name')->get(['id', 'name', 'stock']);
        return response()->json($products);
    }

    public function confirmManualPayment(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->payment_method !== 'cash' || $order->status !== 'waiting_payment') {
            return redirect()->back()->with('error', 'Aksi tidak valid untuk pesanan ini.');
        }

        $order->update([
            'status' => \App\Models\Order::STATUS_COMPLETED,
            'payment_status' => 'verified',
            'notes' => $order->notes . ' (Pembayaran dikonfirmasi manual oleh Admin)',
        ]);

        // Notifikasi ke Owner jika diperlukan
        // $ownerEmail = WebSetting::getSettings()->email;
        // Notification::route('mail', $ownerEmail)->notify(new PaymentVerificationNotification($order, 'manual_confirmed'));

        return redirect()->back()->with('success', 'Pembayaran tunai pesanan #' . $order->order_number . ' berhasil dikonfirmasi secara manual.');
    }
}
