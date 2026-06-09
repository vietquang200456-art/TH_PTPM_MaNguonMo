<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5" style="max-width: 850px;">
    <div class="mb-3">
        <a href="/webbanhang/product" class="text-secondary text-decoration-none small btn-back">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách sản phẩm
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Thêm sản phẩm mới</h2>
        <p class="text-muted small mb-0">Điền đầy đủ thông tin bên dưới để đăng tải sản phẩm mới lên hệ thống cửa hàng.</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-custom alert-danger d-flex align-items-center gap-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-4 text-danger"></i>
            <div class="flex-grow-1">
                <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm custom-form-card">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="/webbanhang/product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
                
                <div class="row g-4">
                    <div class="col-md-12">
                        <label for="name" class="form-label fw-semibold text-secondary">Tên sản phẩm <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-box-open"></i></span>
                            <input type="text" id="name" name="name" class="form-control form-control-custom" placeholder="Ví dụ: Điện thoại iPhone 15 Pro Max" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold text-secondary">Giá bán (VND) <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-tags"></i></span>
                            <input type="number" id="price" name="price" class="form-control form-control-custom" step="0.01" placeholder="Nhập số tiền..." required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold text-secondary">Danh mục sản phẩm <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-layer-group"></i></span>
                            <select id="category_id" name="category_id" class="form-select form-control-custom" required>
                                <option value="" disabled selected hidden>-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold text-secondary">Mô tả chi tiết <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control form-control-custom" rows="4" placeholder="Nhập đặc điểm nổi bật, thông số kỹ thuật sản phẩm..." required></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-secondary">Hình ảnh sản phẩm</label>
                        
                        <div class="row align-items-center g-3">
                            <div class="col-md-8">
                                <div class="file-upload-wrapper">
                                    <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event);">
                                    <div class="file-upload-design">
                                        <i class="fa-solid fa-cloud-arrow-up display-6 text-muted mb-2"></i>
                                        <p class="mb-1 text-dark fw-medium" id="file-label-text">Chọn file hoặc kéo thả ảnh vào đây</p>
                                        <p class="text-muted small mb-0">Hỗ trợ định dạng JPG, PNG, WEBP</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex justify-content-center">
                                <div id="image-preview-container" class="image-preview-box d-flex align-items-center justify-content-center border text-muted">
                                    <div class="text-center" id="preview-placeholder">
                                        <i class="fa-regular fa-image fs-2 mb-1 d-block"></i>
                                        <span class="small" style="font-size: 0.75rem;">Chưa có ảnh</span>
                                    </div>
                                    <img id="img-preview" src="#" alt="Preview" class="d-none w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="/webbanhang/product" class="btn btn-light px-4 py-2.5 border fw-medium text-secondary" style="border-radius: 8px;">Hủy bỏ</a>
                        <button type="submit" class="btn btn-gradient px-5 py-2.5">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Đăng sản phẩm
                        </button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const reader = new FileReader();
    
    reader.onload = function(){
        const imgElement = document.getElementById('img-preview');
        const placeholder = document.getElementById('preview-placeholder');
        
        imgElement.src = reader.result;
        imgElement.classList.remove('d-none');
        placeholder.classList.add('d-none');
    };
    
    if(input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
        // Đổi chữ hiển thị thành tên file được chọn
        document.getElementById('file-label-text').innerText = input.files[0].name;
    }
}
</script>