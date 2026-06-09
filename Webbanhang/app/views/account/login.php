<?php 
require_once 'app/helpers/CsrfHelper.php';
include 'app/views/shares/header.php';
?>

<style>
    body {
        background-color: #f8f9fa;
    }
    .login-card {
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
    .btn-login {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-login:hover {
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
    .social-icon {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #dee2e6;
        color: #6c757d;
        transition: all 0.2s;
        text-decoration: none;
    }
    .social-icon:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 col-lg-5">
            
            <div class="card login-card p-4 p-sm-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-dark mb-2">Chào mừng trở lại</h3>
                    <p class="text-muted small">Vui lòng nhập tên đăng nhập và mật khẩu của bạn</p>
                </div>

                <form action="/webbanhang/account/checklogin" method="post">
                    <?php echo CsrfHelper::renderTokenField(); ?>

                    <div class="mb-3">
                        <label class="form-label small fw-medium text-secondary" for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" class="form-control form-control-user" placeholder="Nhập username" required />
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label small fw-medium text-secondary" for="password">Mật khẩu</label>
                            <a class="small text-decoration-none text-muted mb-2" href="#!">Quên mật khẩu?</a>
                        </div>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control form-control-user input-with-eye" placeholder="Nhập mật khẩu" required />
                            <span class="input-group-text input-group-text-custom" onclick="togglePassword('password', 'eye-icon')">
                                <i class="fa-solid fa-eye" id="eye-icon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button class="btn btn-primary btn-login shadow-sm" type="submit">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small text-muted mb-3">Hoặc đăng nhập bằng</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#!" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#!" class="social-icon"><i class="fab fa-google"></i></a>
                            <a href="#!" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="small text-muted mb-0">Chưa có tài khoản? <a href="/webbanhang/account/register" class="text-decoration-none fw-medium">Đăng ký ngay</a></p>
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
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>