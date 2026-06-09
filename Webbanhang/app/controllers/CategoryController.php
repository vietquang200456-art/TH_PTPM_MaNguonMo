<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/CsrfHelper.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Hàm kiểm tra quyền admin
    private function checkAdmin()
    {
        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
        if (!$isAdmin) {
            http_response_code(403);
            echo "Truy cập bị từ chối! Bạn không có quyền quản trị để thực hiện hành động này.";
            exit;
        }
    }
    // Hàm kiểm tra đăng nhập
    private function checkLogin()
    {
        if (!isset($_SESSION['username'])) {
            http_response_code(401);
            echo "Vui lòng đăng nhập để thực hiện hành động này.";
            exit;
        }
    }
    // Hiển thị danh sách
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // Hiển thị form thêm
    public function add()
    {
        $this->checkAdmin(); // Chỉ admin mới được phép thêm danh mục
        include_once 'app/views/category/add.php';
    }

    // Xử lý lưu dữ liệu
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            // Validate
            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên danh mục không được để trống";
            }

            if (strlen($name) < 3) {
                $errors[] = "Tên danh mục phải lớn hơn 3 ký tự";
            }

            // Nếu có lỗi
            if (count($errors) > 0) {
                include 'app/views/category/add.php';
            } else {

                $result = $this->categoryModel->addCategory($name, $description);

                if ($result) {
                    header('Location: /webbanhang/category/list');
                    exit;
                } else {
                    echo "Thêm danh mục thất bại!";
                }
            }
        }
    }

    // Hiển thị form sửa
    public function edit($id)
    {   
        $this->checkAdmin(); // Chỉ admin mới được phép sửa danh mục
        $category = $this->categoryModel->getCategoryById($id);
        if(!$category){
            echo "Danh mục không tồn tại!";
            return;
        }
        include 'app/views/category/edit.php';
    }

    public function delete($id)
{
    $this->checkAdmin(); // Chỉ admin mới được phép xóa danh mục
    $result = $this->categoryModel->deleteCategory($id);

    if ($result) {
        header('Location: /webbanhang/category/list');
        exit;
    } else {
        echo "Xóa thất bại!";
    }
}
    // Xử lý cập nhật
public function update()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        $errors = [];

        // Validate
        if (empty($name)) {
            $errors[] = "Tên danh mục không được để trống";
        }

        if (strlen($name) < 3) {
            $errors[] = "Tên danh mục phải lớn hơn 3 ký tự";
        }

        // Nếu lỗi
        if (count($errors) > 0) {

            $category = (object)[
                'id' => $id,
                'name' => $name,
                'description' => $description
            ];

            include 'app/views/category/edit.php';

        } else {

            $result = $this->categoryModel->updateCategory(
                $id,
                $name,
                $description
            );

            if ($result) {

                header('Location: /webbanhang/category/list');
                exit;

            } else {

                echo "Cập nhật thất bại!";

            }
        }
    }
}
}
?>