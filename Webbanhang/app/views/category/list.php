<?php
// khởi tạo seassion nếu chưa được khởi tạo
if(session_status() == PHP_SESSION_NONE){
    session_start();   
}
// Kiểm tra xem người dùng hiện tại có phải là admin hay không
// (Thay 'admin' bằng giá trị chuỗi hoặc số tương ứng trong DB của bạn, ví dụ: 'admin' hoặc 1)
$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>
<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Danh sách danh mục</h2>
            <p class="text-muted small mb-0">Quản lý các nhóm danh mục phân loại sản phẩm trên toàn hệ thống cửa hàng.</p>
        </div>
        <?php if ($isAdmin): ?>
        <a href="/webbanhang/category/add" class="btn btn-gradient px-4 py-2.5 text-decoration-none">
            <i class="fa-solid fa-plus me-2"></i> Thêm danh mục mới
        </a>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-sm custom-table-card">
        <div class="card-body p-0"> <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="80">STT</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả chi tiết</th>
                            <th class="text-center" width="180">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php $stt = 1; ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td class="text-center text-muted fw-medium">
                                        <?= sprintf("%02d", $stt++) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="category-icon-wrapper"><i class="fa-solid fa-folder text-indigo"></i></span>
                                            <span class="fw-semibold text-dark"><?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </td>

                                    <td class="text-secondary small text-truncate-custom">
                                        <?= !empty($category->description) ? htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8') : '<em class="text-light-muted">Không có mô tả cho danh mục này</em>' ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($isAdmin): ?>
                                        <div class="d-inline-flex gap-1">
                                            <a href="/webbanhang/category/edit/<?= $category->id ?>" 
                                               class="btn btn-action btn-edit" 
                                               title="Chỉnh sửa danh mục">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <a href="/webbanhang/category/delete/<?= $category->id ?>" 
                                               class="btn btn-action btn-delete" 
                                               title="Xóa danh mục"
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Hãy lưu ý các sản phẩm thuộc danh mục này cũng có thể bị ảnh hưởng.');">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </div>
                                        <?php else: ?>
                                            <span class="text-muted">Không có quyền</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="fa-regular fa-folder-open display-4 text-light-muted"></i></div>
                                    <span class="small">Chưa có danh mục nào được khởi tạo trên hệ thống.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    /* Card chứa bảng bo góc mềm mại */
    .custom-table-card {
        border-radius: 16px;
        background: #ffffff;
        overflow: hidden;
    }

    /* Thiết kế lại Table tinh tế */
    .table-custom thead {
        background-color: #f8fafc; /* Nền xám cực nhẹ sang trọng thay cho màu đen thô */
    }
    .table-custom th {
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        text-uppercase: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-custom td {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }
    .table-custom tbody tr:hover {
        background-color: #f8fafc; /* Hiệu ứng hover hàng mượt mà nhẹ nhàng */
    }

    /* Điểm nhấn nhỏ đầu mục */
    .category-icon-wrapper {
        width: 28px;
        height: 28px;
        background-color: #eeebff;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
    .text-indigo {
        color: #4f46e5;
    }
    .text-light-muted {
        color: #94a3b8;
    }

    /* Giới hạn hiển thị chữ quá dài */
    .text-truncate-custom {
        max-width: 350px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Thiết kế các nút hành động nhỏ nhắn thông minh (Action Buttons) */
    .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .btn-edit { color: #64748b; }
    .btn-edit:hover {
        border-color: #4f46e5;
        background-color: #f5f3ff;
        color: #4f46e5;
    }
    .btn-delete { color: #64748b; }
    .btn-delete:hover {
        border-color: #ef4444;
        background-color: #fef2f2;
        color: #ef4444;
    }

    /* Nút bấm chủ đạo Gradient tím - xanh */
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