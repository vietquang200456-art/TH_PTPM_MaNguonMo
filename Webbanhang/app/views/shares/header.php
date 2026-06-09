<?php 
// Đảm bảo Session đã được khởi tạo trước khi kiểm tra
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra trạng thái đăng nhập dựa trên session 'username'
$isLoggedIn = isset($_SESSION['username']);
// Lấy fullname từ session, nếu không có thì dùng tạm username hoặc chữ 'Thành viên'
$fullName = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'Thành viên');
//Kiểm tra vai trò người dùng để hiển thị các tùy chọn admin
$isAdmin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') || 
           (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/webbanhang/public/css/product-list.css">
    <style>
        /* CSS nhỏ để chỉnh lại màu sắc Dropdown cho đẹp mắt */
        .user-dropdown .dropdown-toggle::after {
            vertical-align: middle;
            margin-left: 5px;
        }
        .user-dropdown .dropdown-item:active {
            background-color: #f8f9fa;
            color: #dc3545;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top custom-navbar shadow-sm">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom" href="/webbanhang/product">
            <i class="fa-solid fa-cubes me-2"></i>PMS SHOP
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto"> 
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/webbanhang/product">
                        <i class="fa-solid fa-boxes-stacked me-1 small"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/webbanhang/category/list">
                        <i class="fa-solid fa-tags me-1 small"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="/webbanhang/product/cart">
                        <i class="fa-solid fa-cart-shopping me-1 small"></i> Giỏ hàng
                    </a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <?php if ($isAdmin): ?>
                <a href="/webbanhang/category/add" class="btn btn-light btn-sm text-secondary border" style="border-radius: 8px; padding: 0.5rem 0.8rem;">
                    <i class="fa-solid fa-folder-plus me-1"></i> + Danh mục
                </a>
                <a href="/webbanhang/product/add" class="btn btn-quick-add btn-sm">
                    <i class="fa-solid fa-plus-circle me-1"></i> Thêm sản phẩm
                </a>
                <?php endif; ?>
                <?php if ($isLoggedIn): ?>
                    <div class="dropdown user-dropdown" id="nav-logout">
                        <button class="btn btn-link nav-link dropdown-toggle p-2 d-flex align-items-center text-dark text-decoration-none fw-medium" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user fs-5 me-2 text-primary"></i>
                            <span>Chào, <?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu" style="border-radius: 10px;">
                            <li>
                                <a class="dropdown-item py-2 text-danger small" href="/webbanhang/account/logout">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div id="nav-login">
                        <a class="nav-link p-2 fw-medium" href="/webbanhang/account/login">
                            <i class="fa-solid fa-user me-1"></i> Đăng nhập
                        </a>
                        <a class="nav-link p-2 fw-medium" href="/webbanhang/account/register">
                            <i class="fa-solid fa-user-plus me-1"></i> Đăng ký
                        </a>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 text-center">
    <h2 class="fw-bold">Mua sắm dễ dàng - Giá tốt mỗi ngày</h2>
    <p class="text-muted">
        Tìm kiếm sản phẩm yêu thích, đặt hàng nhanh chóng và tận hưởng ưu đãi hấp dẫn.
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>