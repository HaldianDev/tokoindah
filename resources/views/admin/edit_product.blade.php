@extends('layouts.dashboard')

@section('page_title', 'Edit Produk Keramik')

@section('topbar_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn-action btn-outline btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
@endsection

@section('content')
<div class="space-y" style="max-width: 900px; margin: 0 auto;">
    
    <div class="table-card">
        <div class="table-card-header" style="background: #F8FAFC;">
            <div class="table-card-title">
                <div class="table-card-title-icon" style="background: rgba(2,132,199,0.1); color: #0284C7;">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #0F172A;">Edit Data Produk: {{ $product->name }}</h3>
                    <p style="font-size: 0.78rem; color: #64748B; font-weight: 500;">Perbarui informasi harga, stok, berat pengiriman, kategori, dan foto produk.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data" style="padding: 2rem;">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                
                <!-- Nama Produk -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Nama Produk Keramik <span style="color:#EF4444;">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}" placeholder="Contoh: Set Piring Porselen Royal Blue 6 Pcs">
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori Produk <span style="color:#EF4444;">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga Satuan -->
                <div class="form-group">
                    <label class="form-label">Harga Satuan (Rp) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="price" class="form-control" required min="0" value="{{ old('price', $product->price) }}" placeholder="150000">
                </div>

                <!-- Jumlah Stok -->
                <div class="form-group">
                    <label class="form-label">Jumlah Stok Tersedia <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="stock" class="form-control" required min="0" value="{{ old('stock', $product->stock) }}" placeholder="20">
                </div>

                <!-- Berat Barang (gram) -->
                <div class="form-group">
                    <label class="form-label">Berat Produk (Gram) <span style="color:#EF4444;">*</span></label>
                    <input type="number" name="weight" class="form-control" required min="1" value="{{ old('weight', $product->weight ?: 1000) }}" placeholder="1000">
                    <small style="color: #64748B; font-size: 0.72rem;">*Digunakan untuk kalkulasi ongkos kirim kurir (1.000 gram = 1 kg).</small>
                </div>

                <!-- Gambar Produk -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Foto / Gambar Produk</label>
                    <div style="display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
                        <div style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; border: 2px solid #E2E8F0; flex-shrink: 0; background: #F8FAFC;">
                            <img id="previewImg" src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex: 1; min-width: 240px;">
                            <input type="file" name="image_file" accept="image/*" class="form-control" onchange="previewUpload(event)" style="padding: 0.45rem;">
                            <p style="font-size: 0.72rem; color: #64748B; margin-top: 4px;">Pilih file dari komputer (Format: JPG, PNG, WEBP, maks 3MB). Biarkan kosong jika tidak ingin mengubah foto.</p>
                            
                            <div style="margin-top: 8px;">
                                <label style="font-size: 0.75rem; color: #64748B; display: block;">Atau masukkan URL Gambar Cadangan:</label>
                                <input type="url" name="image_url" class="form-control" style="font-size: 0.8rem; padding: 0.4rem 0.6rem;" value="{{ old('image_url', filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : '') }}" placeholder="https://images.unsplash.com/...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label">Deskripsi Lengkap Produk</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Jelaskan detail produk, material, fungsi, dan keunggulannya...">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Spesifikasi -->
                <div class="form-group">
                    <label class="form-label">Spesifikasi 1</label>
                    <input type="text" name="spec_1" class="form-control" value="{{ old('spec_1', $product->spec_1) }}" placeholder="Contoh: Material Porselen Tebal">
                </div>

                <div class="form-group">
                    <label class="form-label">Spesifikasi 2</label>
                    <input type="text" name="spec_2" class="form-control" value="{{ old('spec_2', $product->spec_2) }}" placeholder="Contoh: Glasir Halus Tahan Panas">
                </div>

                <div class="form-group">
                    <label class="form-label">Spesifikasi 3</label>
                    <input type="text" name="spec_3" class="form-control" value="{{ old('spec_3', $product->spec_3) }}" placeholder="Contoh: Garansi Pengiriman Aman">
                </div>

            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #E2E8F0; display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('admin.dashboard') }}" class="btn-action btn-outline">
                    Batal
                </a>
                <button type="submit" class="btn-action btn-primary" style="padding: 0.75rem 1.75rem;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Produk
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function previewUpload(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
