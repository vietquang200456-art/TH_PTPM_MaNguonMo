<?php 
require_once 'app/helpers/CsrfHelper.php';
include 'app/views/shares/header.php';
?>

<style>
    body {
        background-color: #f8f9fa;
    }
    .register-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }
    .form-control-user {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
    }
    .form-control-user:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .btn-register {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .input-group-text-custom {
        border-top-right-radius: 10px !important;
        border-bottom-right-radius: 10px !important;
        background-color: #fff;
        border-left: none;
        cursor: pointer;
        color: #6c757d;
    }
    .input-with-eye {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none;
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i>
                        <div>
                            <?php foreach ($errors as $err): ?>
                                <div><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card register-card p-4 p-sm-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-2">Tạo tài khoản mới</h3>
                    <p class="text-muted small">Vui lòng điền đầy đủ thông tin phía dưới để đăng ký</p>
                </div>

                <form class="user" action="/webbanhang/account/save" method="post">
                    <?php echo CsrfHelper::renderTokenField(); ?>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="username" class="form-label small fw-medium text-secondary">Tên đăng nhập</label>
                            <input type="text" class="form-control form-control-user"
                                id="username" name="username" placeholder="Nhập username" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="fullname" class="form-label small fw-medium text-secondary">Họ và tên</label>
                            <input type="text" class="form-control form-control-user"
                                id="fullname" name="fullname" placeholder="Nhập họ tên" required>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="password" class="form-label small fw-medium text-secondary">Mật khẩu</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-user input-with-eye"
                                    id="password" name="password" placeholder="Nhập mật khẩu" required>
                                <span class="input-group-text input-group-text-custom" onclick="togglePassword('password', 'eye-icon-1')">
                                    <i class="fa-solid fa-eye" id="eye-icon-1"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-sm-6">
                            <label for="confirmpassword" class="form-label small fw-medium text-secondary">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-user input-with-eye"
                                    id="confirmpassword" name="confirmpassword" placeholder="Nhập lại mật khẩu" required>
                                <span class="input-group-text input-group-text-custom" onclick="togglePassword('confirmpassword', 'eye-icon-2')">
                                    <i class="fa-solid fa-eye" id="eye-icon-2"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-register shadow-sm" type="submit">
                            <i class="fa-solid fa-user-plus me-2"></i> Đăng Ký Ngay
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Đã có tài khoản? <a href="/webbanhang/account/login" class="text-decoration-none fw-medium">Đăng nhập</a></p>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Đổi icon thành mắt gạch chéo
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            // Đổi icon lại thành mắt thường
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>