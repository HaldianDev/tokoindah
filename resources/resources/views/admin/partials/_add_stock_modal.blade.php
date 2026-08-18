<div class="modal-overlay" id="addStockModal">
    <div class="modal-card" style="max-width: 500px;">
        <button class="modal-close" onclick="document.getElementById('addStockModal').classList.remove('active')">&times;</button>
        
        <div style="border-bottom: 1px solid #E2E8F0; padding-bottom: 0.8rem; margin-bottom: 1.2rem;">
            <h3 style="font-weight: 800; color: #0F172A; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-boxes-packing" style="color: #059669;"></i> Input Pasokan Barang Masuk
            </h3>
            <p style="font-size: 0.78rem; color: #64748B;">
                Penambahan stok akan otomatis menambah kuantitas produk dan dicatat dalam riwayat mutasi stok untuk dipantau Owner.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.stock.add') }}">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px; color:#0F172A;">
                        Pilih Kategori <span style="color:#EF4444;">*</span>
                    </label>
                    <select id="stockAddCategory" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($allCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px; color:#0F172A;">
                        Pilih Produk Keramik <span style="color:#EF4444;">*</span>
                    </label>
                    <select name="product_id" id="stockAddProduct" required class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" disabled>
                        <option value="">-- Pilih Kategori Dulu --</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <p style="font-size: 0.7rem; color: #64748B; margin-top: 4px;">Jika produk belum pernah ada sama sekali di sistem, silakan buat melalui menu "Tambah Produk".</p>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px; color:#0F172A;">
                    Jumlah Stok Masuk (Unit) <span style="color:#EF4444;">*</span>
                </label>
                <input type="number" name="quantity" required min="1" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="Contoh: 25">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px; color:#0F172A;">
                    Catatan Pasokan / Supplier (Opsional)
                </label>
                <input type="text" name="notes" class="form-control" style="width: 100%; padding: 0.65rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="Contoh: Pasokan pabrik baru batch 2026">
            </div>

            <button type="submit" class="btn-action btn-success" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem; border-radius: 10px;">
                <i class="fa-solid fa-plus-circle"></i> Konfirmasi & Tambah Stok Masuk
            </button>
        </form>
    </div>
</div>
