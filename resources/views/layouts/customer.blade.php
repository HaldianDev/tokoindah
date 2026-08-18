@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    <div class="grid md:grid-cols-12 gap-8">
        
        <!-- Sidebar -->
        <div class="md:col-span-3">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-4 border-b border-slate-100 pb-5 mb-5">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-full object-cover border-4 border-slate-100">
                    <div>
                        <h2 class="font-bold text-slate-900 text-lg">{{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('customer.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold text-sm {{ request()->routeIs('customer.orders') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-receipt w-4 text-center"></i>
                        <span>Pesanan & Angsuran</span>
                    </a>
                    <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold text-sm {{ request()->routeIs('customer.settings') ? 'bg-sky-100 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-user-cog w-4 text-center"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                     <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-semibold text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 w-full text-left">
                           <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-9">
            @yield('customer_content')
        </div>

    </div>
</div>
@endsection
