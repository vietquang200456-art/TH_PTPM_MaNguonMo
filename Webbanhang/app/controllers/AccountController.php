<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/CsrfHelper.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        // SỬA TẠI ĐÂY: Khởi tạo session ngay khi controller được gọi
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    /**
     * Hiển thị form đăng ký
     */
    public function register() {
        CsrfHelper::generateToken();
        include_once 'app/views/account/register.php';
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function login() {
        CsrfHelper::generateToken();
        include_once 'app/views/account/login.php';
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!CsrfHelper::verifyToken($csrf_token)) {
                die('CSRF token không hợp lệ!');
            }

            $username = trim($_POST['username'] ?? '');
            $fullName = trim($_POST['fullname'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirmpassword'] ?? '');
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập username!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập họ tên!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận không khớp";
            }

            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            if (count($errors) > 0) {
                CsrfHelper::generateToken();
                include_once 'app/views/account/register.php';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $hashedPassword);
                
                if ($result) {
                    header('Location: /webbanhang/account/login');
                    exit;
                } else {
                    echo "Đăng ký thất bại!";
                }
            }
        }
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        // SỬA TẠI ĐÂY: Xóa sạch dữ liệu mảng session trước khi hủy
        $_SESSION = array();
        session_destroy();
        header('Location: /webbanhang/product');
        exit;
    }

    /**
     * Xử lý đăng nhập
     */
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!CsrfHelper::verifyToken($csrf_token)) {
                die('CSRF token không hợp lệ!');
            }

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                echo "Tên đăng nhập và mật khẩu không được để trống!";
                return;
            }

            $account = $this->accountModel->getAccountByUsername($username);
            
            if ($account) {
                $pwd_hashed = $account->password;
                
                if (password_verify($password, $pwd_hashed)) {
                    session_regenerate_id(true);
                    
                    // Thiết lập các biến Session
                    $_SESSION['user_id'] = $account->id;
                    $_SESSION['username'] = $account->username;
                    $_SESSION['user_role'] = $account->role;
                    $_SESSION['fullname'] = $account->fullname ?? '';
                    
                    header('Location: /webbanhang/product');
                    exit;
                } else {
                    echo "Mật khẩu không đúng.";
                }
            } else {
                echo "Không tìm thấy tài khoản.";
            }
        }
    }
}
?>
