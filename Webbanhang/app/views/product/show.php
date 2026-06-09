<?php include 'app/views/shares/header.php'; ?>

<?php
$display_category_name =
    !empty($product->category_name)
    ? $product->category_name
    : 'Chưa phân loại';
// Khởi tạo seassion nếu chưa được khởi tạo
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
//Kiểm tra vai trò người dùng để hiển thị các tùy chọn admin
$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') || 
           (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="/webbanhang/product" class="text-secondary text-decoration-none small">Cửa hàng</a></li>
                <li class="breadcrumb-item text-muted small active" aria-current="page">Chi tiết sản phẩm</li>
            </ol>
        </nav>
        <a href="/webbanhang/product" class="text-secondary text-decoration-none small btn-back">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm product-detail-card" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-4 p-md-5">
            <div class="row g-5">
                
                <div class="col-lg-6">
                    <div class="product-gallery-wrapper rounded-4 border shadow-sm position-relative">
                        <span class="position-absolute badge bg-blur m-3 z-1">
                            <i class="fa-regular fa-folder me-1"></i>
                            <?php echo htmlspecialchars($display_category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </span>

                        <?php if (!empty($product->image)): ?>
                            <img src="/webbanhang/<?php echo $product->image; ?>" class="img-fluid w-100 h-100 product-main-img" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted" style="min-height: 400px;">
                                <i class="fa-regular fa-image display-1 mb-3"></i>
                                <span class="text-uppercase fw-semibold small" style="letter-spacing: 1px;">Hình ảnh chưa cập nhật</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6 d-flex flex-column justify-content-between">
                    <div class="product-info-section">
                        <h1 class="fw-bold text-dark mb-3 lh-sm" style="font-size: 2rem;">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h1>
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <p class="text-muted small mb-0">
                                <i class="fa-solid fa-tag me-1"></i> Danh mục: 
                                <span class="fw-semibold text-indigo ms-1"><?php echo htmlspecialchars($display_category_name, ENT_QUOTES, 'UTF-8'); ?></span>
                            </p>
                        </div>

                        <div class="price-box p-3 rounded-3 mb-4 d-inline-flex align-items-baseline gap-2">
                            <span class="small text-muted fw-medium">Giá ưu đãi:</span>
                            <span class="fs-2 fw-bold text-danger">
                                <?php echo number_format((float)$product->price, 0, ',', '.'); ?><span class="fs-5 fw-normal">đ</span>
                            </span>
                        </div>

                        <div class="description-box mb-4">
                            <h5 class="fw-bold text-secondary mb-2" style="font-size: 1rem; letter-spacing: 0.5px; text-uppercase: uppercase;">Mô tả sản phẩm</h5>
                            <div class="p-3 bg-light rounded-3 text-secondary border" style="line-height: 1.7; font-size: 0.95rem; white-space: pre-line;">
                                <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="action-section pt-4 border-top">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="/webbanhang/product/addToCart/<?php echo $product->id; ?>"
                                   class="btn btn-gradient w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                                    <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ hàng
                                </a>
                            </div>
                            <?php if($isAdmin): ?>
                            <div class="col-sm-6 d-flex gap-2">
                                <a href="/webbanhang/product/edit/<?php echo $product->id; ?>" class="btn btn-outline-secondary w-50 py-2.5 fw-medium d-flex align-items-center justify-content-center gap-1">
                                    <i class="fa-regular fa-pen-to-square"></i> Sửa
                                </a>
                                <a href="/webbanhang/product/delete/<?php echo $product->id; ?>" class="btn btn-outline-danger w-50 py-2.5 fw-medium d-flex align-items-center justify-content-center gap-1" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <i class="fa-regular fa-trash-can"></i> Xóa
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>
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

    .product-detail-card {
        background: #ffffff;
    }

    .product-gallery-wrapper {
        width: 100%;
        padding-top: 100%;
        position: relative;
        overflow: hidden;
        background-color: #ffffff;
    }
    .product-main-img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: contain;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }
    .product-gallery-wrapper:hover .product-main-img {
        transform: scale(1.03);
    }

    .price-box {
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        width: 100%;
    }

    .bg-blur {
        background: rgba(255, 255, 255, 0.85);
        color: #111827;
        backdrop-filter: blur(4px);
        font-weight: 500;
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 0.4rem 0.7rem;
        border-radius: 8px;
    }

    .text-indigo {
        color: #4f46e5 !important;
    }

    /* Thiết kế nút bấm màu Gradient chuẩn tone */
    .btn-gradient {
        background: linear-gradient(45deg, #4f46e5, #06b6d4);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>