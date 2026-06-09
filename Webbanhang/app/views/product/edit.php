<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5" style="max-width: 850px;">
    <div class="mb-3">
        <a href="/webbanhang/product" class="text-secondary text-decoration-none small btn-back">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách sản phẩm
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Cập nhật sản phẩm</h2>
        <p class="text-muted small mb-0">Chỉnh sửa thông tin chi tiết của sản phẩm hiện có trong hệ thống.</p>
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
            <form method="POST" action="/webbanhang/product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                
                <div class="row g-4">
                    <div class="col-md-12">
                        <label for="name" class="form-label fw-semibold text-secondary">Tên sản phẩm <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-box-open"></i></span>
                            <input type="text" id="name" name="name" class="form-control form-control-custom" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold text-secondary">Giá bán (VND) <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-tags"></i></span>
                            <input type="number" id="price" name="price" class="form-control form-control-custom" step="0.01" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-semibold text-secondary">Danh mục sản phẩm <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-layer-group"></i></span>
                            <select id="category_id" name="category_id" class="form-select form-control-custom" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold text-secondary">Mô tả chi tiết <span class="text-danger">*</span></label>
                        <textarea id="description" name="description" class="form-control form-control-custom" rows="4" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-secondary">Hình ảnh sản phẩm</label>
                        <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">
                        
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <div class="file-upload-wrapper">
                                    <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event);">
                                    <div class="file-upload-design">
                                        <i class="fa-solid fa-cloud-arrow-up fs-3 text-muted mb-1"></i>
                                        <p class="mb-0 text-dark small fw-medium" id="file-label-text">Chọn ảnh mới để thay thế</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 d-flex justify-content-center justify-content-md-start gap-3">
                                <div class="text-center">
                                    <span class="badge bg-light text-dark border mb-1 fw-normal" style="font-size: 0.7rem;">Ảnh hiện tại</span>
                                    <div class="image-box border shadow-sm">
                                        <?php if (!empty($product->image)): ?>
                                            <img src="/webbanhang/<?php echo $product->image; ?>" alt="Current Product Image" class="w-100 h-100" style="object-fit: cover;">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted small">Không ảnh</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="text-center d-none" id="preview-block">
                                    <span class="badge bg-primary mb-1 fw-normal text-white" style="font-size: 0.7rem;">Ảnh mới thay thế</span>
                                    <div class="image-box border shadow-sm border-primary">
                                        <img id="img-preview" src="#" alt="New Preview" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="/webbanhang/product" class="btn btn-light px-4 py-2.5 border fw-medium text-secondary" style="border-radius: 8px;">Hủy bỏ</a>
                        <button type="submit" class="btn btn-gradient px-5 py-2.5">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
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
        const previewBlock = document.getElementById('preview-block');
        
        imgElement.src = reader.result;
        previewBlock.classList.remove('d-none'); // Hiển thị khối ảnh mới
    };
    
    if(input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
        document.getElementById('file-label-text').innerText = input.files[0].name;
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>