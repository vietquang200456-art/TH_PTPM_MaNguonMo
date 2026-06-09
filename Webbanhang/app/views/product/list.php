<?php 
// Khởi tạo session nếu chưa được bật
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'app/views/shares/header.php'; 
//Kiểm tra trạng thái đăng nhập 
$isLoggedIn = isset($_SESSION['username']);

// Kiểm tra xem người dùng hiện tại có phải là admin hay không
$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>

<div class="container my-5">

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3
                mb-4">

        <div>
            <h2 class="fw-bold text-dark mb-1">
                Khám phá sản phẩm
            </h2>
            <p class="text-muted small mb-0">
                Tìm kiếm và chọn lựa những sản phẩm chất lượng nhất dành cho bạn.
            </p>
        </div>

        <?php if ($isAdmin): ?>
            <a href="/webbanhang/product/add"
               class="btn btn-gradient
                      d-inline-flex
                      align-items-center
                      gap-2
                      px-4
                      py-2">
                <i class="fa-solid fa-plus-circle"></i>
                Thêm sản phẩm mới
            </a>
        <?php endif; ?>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-5 custom-search-card">
        <div class="card-body p-4">
            <form method="GET" action="/webbanhang/product">
                <div class="row g-3 align-items-center">

                    <div class="col-lg-6 col-md-12">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            <i class="fa-solid fa-magnifying-glass me-1 text-muted"></i> Tìm kiếm sản phẩm
                        </label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-search"></i></span>
                            <input 
                                type="text" 
                                name="keyword" 
                                class="form-control form-control-custom" 
                                placeholder="Nhập tên sản phẩm, thương hiệu cần tìm..."
                                value="<?php echo htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-8">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            <i class="fa-solid fa-layer-group me-1 text-muted"></i> Phân loại danh mục
                        </label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fa-solid fa-filter"></i></span>
                            <select name="category_id" class="form-select form-control-custom">
                                <option value="">Tất cả danh mục sản phẩm</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4 mt-lg-4 pt-lg-2">
                        <button type="submit" class="btn btn-gradient w-100 py-2.5">
                            <i class="fa-solid fa-sliders me-2"></i> Tìm kiếm
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-xl-3 col-lg-4 col-sm-6 col-12">
                    <div class="card h-100 product-card border-0 shadow-sm">
                        
                        <span class="position-absolute badge bg-blur m-3 z-1">
                            <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </span>

                        <div class="product-img-wrapper">
                            <?php if (!empty($product->image)): ?>
                                <img src="/Webbanhang/<?php echo $product->image; ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                                    <i class="fa-regular fa-image fs-1 mb-2"></i>
                                    <small class="text-uppercase" style="font-size:0.7rem; letter-spacing:1px;">No Image</small>
                                </div>
                            <?php endif; ?>
                            
                            <div class="img-overlay">
                                <a href="/webbanhang/product/show/<?php echo $product->id; ?>" class="btn btn-light btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                                    <i class="fa-regular fa-eye me-1"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title mb-2">
                                <a href="/webbanhang/product/show/<?php echo $product->id; ?>" class="text-dark text-decoration-none product-title text-truncate-2">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small text-truncate-2 mb-3">
                                <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </p>

                            <div class="mt-auto pt-3 border-top d-flex flex-column gap-2">
                                <div class="d-flex align-items-baseline justify-content-between">
                                    <span class="small text-muted">Giá bán:</span>
                                    <span class="fs-5 fw-bold text-danger">
                                        <?php echo number_format((float)$product->price, 0, ',', '.'); ?>
                                        <span class="fs-6 fw-normal">Vnđ</span>
                                    </span>
                                </div>

                                <?php if ($isAdmin): ?>
                                    <div class="d-flex gap-2 mt-1">
                                        <a href="/Webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-secondary w-50 py-1 border-dashed">
                                            <i class="fa-regular fa-pen-to-square me-1"></i> Sửa
                                        </a>
                                        <a href="/Webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-danger w-50 py-1" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <i class="fa-regular fa-trash-can me-1"></i> Xóa
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($isLoggedIn): ?>
                                <a href="/Webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary w-100 py-2">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
                                </a>
                                <?php else: ?>
                                    <a href="/webbanhang/account/login" class="btn btn-sm btn-outline-primary w-100 py-2" onclick="alert('Vui lòng đăng nhập tài khoản để thực hiện chức năng thêm vào giỏ hàng!');">
                                        <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
                                    </a>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-box-open d-block display-4 mb-3 text-secondary"></i>
                <h5 class="fw-bold">Không tìm thấy sản phẩm</h5>
                <p class="small">Hãy thử tìm kiếm bằng từ khóa khác.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>