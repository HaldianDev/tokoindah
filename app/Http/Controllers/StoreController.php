<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\WebSetting;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Halaman Utama / Beranda
     */
    public function home()
    {
        $settings = WebSetting::getSettings();
        $categories = Category::all();
        $featuredProducts = Product::with('category')->latest()->take(8)->get();
        $totalProducts = Product::count();

        return view('store.home', compact('settings', 'categories', 'featuredProducts', 'totalProducts'));
    }

    /**
     * Halaman Katalog Belanja Lengkap
     */
    public function catalog(Request $request)
    {
        $settings = WebSetting::getSettings();
        $categories = Category::all();
        $totalProducts = Product::count();

        $query = Product::with('category');

        if ($request->has('category') && $request->category != 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        $products = $query->latest()->get();

        return view('store.index', compact('settings', 'categories', 'products', 'totalProducts'));
    }

    /**
     * Halaman Tentang Kami (About Us)
     */
    public function about()
    {
        $settings = WebSetting::getSettings();
        $categories = Category::all();

        return view('store.about', compact('settings', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }
}
