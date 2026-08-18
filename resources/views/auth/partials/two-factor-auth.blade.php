
<div class="table-card" style="padding: 1.5rem 2rem; margin-top: 2rem;">
    <div class="space-y-6">
        <h3 class="text-lg font-bold text-slate-800">Autentikasi Dua Faktor (2FA)</h3>
        
        @if (session('status') == 'two-factor-authentication-enabled')
            <div class="flash-alert flash-success">
                <i class="fa-solid fa-shield-check"></i>
                Autentikasi dua faktor telah diaktifkan. Pindai QR code berikut dengan aplikasi autentikator Anda.
            </div>
        @endif

        <div class="max-w-xl text-sm text-gray-600">
            <p>
                Tambahkan keamanan ekstra pada akun Anda dengan menggunakan autentikasi dua faktor.
            </p>
        </div>

        @if (auth()->user()->two_factor_secret)
            @if (session('status') == 'two-factor-authentication-enabled')
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                    </p>
                </div>

                <div class="mt-4">
                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            <div class="mt-4 max-w-xl text-sm text-gray-600">
                <p class="font-semibold">
                    Simpan recovery code ini di tempat yang aman. Recovery code ini dapat digunakan untuk mengakses akun Anda jika perangkat autentikasi dua faktor Anda hilang.
                </p>
            </div>

            <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                    <div>{{ $code }}</div>
                @endforeach
            </div>

            <div class="flex items-center mt-5">
                <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}">
                    @csrf
                    <button type="submit" class="btn-action btn-secondary mr-3">
                        Regenerate Recovery Codes
                    </button>
                </form>
                
                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-danger">
                        Nonaktifkan 2FA
                    </button>
                </form>
            </div>
        @else
            <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                @csrf
                <button type="submit" class="btn-action btn-primary">
                    Aktifkan 2FA
                </button>
            </form>
        @endif
    </div>
</div>
