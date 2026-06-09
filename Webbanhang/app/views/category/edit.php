<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5" style="max-width: 750px;">
    <div class="mb-3">
        <a href="/webbanhang/category/list" class="text-secondary text-decoration-none small btn-back">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại danh sách danh mục
        </a>
    </div>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Cập nhật danh mục</h2>
        <p class="text-muted small mb-0">Chỉnh sửa tên và thông tin mô tả cho nhóm danh mục sản phẩm hiện có.</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-custom alert-danger d-flex align-items-center gap-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation fs-4 text-danger"></i>
            <div class="flex-grow-1">
                <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm custom-form-card">
        <div class="card-body p-4 p-md-5">
            <form action="/webbanhang/category/update" method="POST">
                
                <input type="hidden" name="id" value="<?= $category->id ?>">

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Tên danh mục <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-folder-open"></i></span>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control form-control-custom" 
                                value="<?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>" 
                                placeholder="Nhập tên danh mục..." 
                                required
                            >
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary">Mô tả danh mục</label>
                        <textarea 
                            name="description" 
                            class="form-control form-control-custom textarea-custom" 
                            rows="5" 
                            placeholder="Nhập vài dòng mô tả đặc trưng của nhóm danh mục này..."
                        ><?= htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="col-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="/webbanhang/category/list" class="btn btn-light px-4 py-2.5 border fw-medium text-secondary" style="border-radius: 8px;">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-gradient px-5 py-2.5">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
    .btn-back {
        transition: color 0.2s ease;
    }
    .btn-back:hover {
        color: #4f46e5 !important;
    }

    .custom-form-card {
        border-radius: 16px;
        background: #ffffff;
    }

    .alert-custom {
        border-radius: 12px;
        border: none;
    }

    /* Thiết kế Input Group có chứa Icon bên trái */
    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon {
        position: absolute;
        left: 14px;
        color: #9ca3af;
        z-index: 5;
        pointer-events: none;
    }
    .form-control-custom {
        padding-left: 42px !important;
        border-radius: 8px !important;
        border: 1px solid #d1d5db;
        padding-top: 0.6rem;
        padding-bottom: 0.6rem;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    
    /* Textarea tùy biến không cần khoảng cách thụt lề icon */
    .textarea-custom {
        padding-left: 16px !important;
    }

    /* Nút lưu thay đổi dạng Gradient tím - xanh giống nút thêm sản phẩm */
    .btn-gradient {
        background: linear-gradient(45deg, #4f46e5, #06b6d4);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>