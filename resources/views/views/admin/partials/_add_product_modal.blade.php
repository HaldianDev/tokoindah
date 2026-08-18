<div class="modal-overlay" id="addProductModal">
    <div class="modal-card" style="max-width: 650px;">
        <button class="modal-close" onclick="document.getElementById('addProductModal').classList.remove('active')">&times;</button>
        <div style="border-bottom: 1px solid #E2E8F0; padding-bottom: 0.8rem; margin-bottom: 1.2rem;">
            <h3 style="font-weight: 800; color: #0F172A; font-size: 1.15rem; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus-circle" style="color: #0284C7;"></i> Tambah Produk Keramik Baru
            </h3>
            <p style="font-size: 0.78rem; color: #64748B;">Lengkapi detail produk, kategori, berat pengiriman, dan upload foto produk.</p>
        </div>

        <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Pilih Kategori <span style="color:#EF4444;">*</span></label>
                    <select name="category_id" required class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($allCategories ?? $categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <small style="color:#64748B; font-size:0.7rem;">Belum ada kategori? Tambah di menu Kategori.</small>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Nama Produk <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" required class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="Contoh: Piring Keramik Vintage 8 Inch">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Harga (Rp) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="price" required min="0" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="150000">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Stok Awal <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="stock" required min="0" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="20">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Berat (Gram) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="weight" required min="1" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="500" value="1000">
                </div>
            </div>

            <!-- Upload Gambar Produk -->
            <div style="margin-bottom: 1rem; background: #F8FAFC; padding: 0.85rem; border-radius: 10px; border: 1px dashed #CBD5E1;">
                <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px; color: #0F172A;">
                    <i class="fa-solid fa-image" style="color: #0284C7;"></i> Upload Foto Produk (File Komputer / HP)
                </label>
                <input type="file" name="image_file" accept="image/*" class="form-control" style="width: 100%; padding: 0.4rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.8rem; background:#fff;">
                <p style="font-size: 0.72rem; color: #64748B; margin-top: 4px;">Format didukung: JPG, PNG, WEBP (Maksimal 3MB).</p>
                
                <div style="margin-top: 6px;">
                    <label style="font-size: 0.72rem; color: #64748B; display: block;">Atau gunakan URL Gambar (Opsional):</label>
                    <input type="url" name="image_url" placeholder="https://images.unsplash.com/..." class="form-control" style="width: 100%; padding: 0.35rem 0.6rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.75rem; background:#fff;">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 700; font-size: 0.82rem; display: block; margin-bottom: 4px;">Deskripsi Produk</label>
                <textarea name="description" rows="2" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem;" placeholder="Penjelasan detail bahan keramik, keunggulan tahan panas, finishing glasir, dll..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.8rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; display: block; margin-bottom: 4px;">Spesifikasi 1</label>
                    <input type="text" name="spec_1" value="Keramik Mutu Tinggi & Halus" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.78rem;">
                </div>
                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; display: block; margin-bottom: 4px;">Spesifikasi 2</label>
                    <input type="text" name="spec_2" value="Tahan Panas & Food Grade" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.78rem;">
                </div>
                <div>
                    <label style="font-weight: 700; font-size: 0.75rem; display: block; margin-bottom: 4px;">Spesifikasi 3</label>
                    <input type="text" name="spec_3" value="Garansi Pengiriman Aman" class="form-control" style="width: 100%; padding: 0.45rem; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.78rem;">
                </div>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem; border-radius: 10px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan & Publikasikan Produk Baru
            </button>
        </form>
    </div>
</div>
