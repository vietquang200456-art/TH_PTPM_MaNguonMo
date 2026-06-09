<?php
// Require các file cần thiết
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    // Khai báo thuộc tính
    private $productModel;
    private $db;    
    // Hàm khởi tạo
    // Tự động chạy khi controller được gọi
    public function __construct()
    {
        // Kết nối database
        $this->db = (new Database())->getConnection();
        // Khởi tạo ProductModel
        $this->productModel = new ProductModel($this->db);
    }
    // Hiển thị danh sách sản phẩm
    public function index()
{
    // Lấy keyword tìm kiếm
    $keyword = $_GET['keyword'] ?? '';
    // Lấy category
    $category_id = $_GET['category_id'] ?? '';
    // Lấy danh sách category
    $categories =
        (new CategoryModel($this->db))
        ->getCategories();

    // Lấy sản phẩm có filter
    $products =
        $this->productModel
        ->getFilteredProducts(
            $keyword,
            $category_id
        );

    include 'app/views/product/list.php';
}
    // Hàm kiểm tra quyền admin
    private function checkAdmin(){
        $isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin' || (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')  );
        if (!$isAdmin) {
            // Nếu không phải admin, hiển thị thông báo lỗi hoặc chuyển hướng
            http_response_code(403);
            echo "Truy cập bị từ chối! Bạn không có quyền quản trị để thực hiện hành động này.";
            exit;
        }
    }
    // Hàm kiểm tra trạng thái đăng nhập
    private function checkLogin(){
        if(!isset($_SESSION['username'])){
            //Nếu chưa đăng nhập thì thông báo và chuyển hướng
            echo "<script>alert('Vui lòng đăng nhập để truy cập trang này!'); window.location.href = '/webbanhang/account/login';</script>";
            exit;
        }
    }

    // =====================================================
    // Hiển thị chi tiết sản phẩm
    // URL: /Product/show/id
    // =====================================================
    public function show($id)
    {
        // Lấy sản phẩm theo id
        $product = $this->productModel->getProductById($id);

        // Kiểm tra sản phẩm tồn tại
        if ($product) {

            // Gọi view chi tiết
            include 'app/views/product/show.php';

        } else {

            // Nếu không tìm thấy
            echo "Không thấy sản phẩm.";
        }
    }

    // =====================================================
    // Hiển thị form thêm sản phẩm
    // URL: /Product/add
    // =====================================================
    public function add()
    {
        $this->checkAdmin();
        // Lấy danh sách category
        $categories = (new CategoryModel($this->db))->getCategories();
        // Gọi form thêm
        include_once 'app/views/product/add.php';
    }

    // =====================================================
    // Xử lý lưu sản phẩm mới
    // =====================================================
    public function save()
    {
        // Kiểm tra request POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // ===============================
            // Lấy dữ liệu từ form
            // ===============================
            $name = trim($_POST['name'] ?? '');

            $description = trim($_POST['description'] ?? '');

            $price = trim($_POST['price'] ?? '');

            $category_id = $_POST['category_id'] ?? null;

            // ===============================
            // Validate dữ liệu
            // ===============================
            $errors = [];

            // Kiểm tra tên sản phẩm
            if (empty($name)) {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            // Kiểm tra giá
            if (empty($price)) {
                $errors[] = "Giá sản phẩm không được để trống.";
            }

            // Kiểm tra giá hợp lệ
            if (!is_numeric($price) || $price < 0) {
                $errors[] = "Giá sản phẩm không hợp lệ.";
            }

            // Kiểm tra category
            if (empty($category_id)) {
                $errors[] = "Vui lòng chọn danh mục.";
            }

            // ===============================
            // Upload ảnh
            // ===============================
            $image = "";

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

                try {

                    // Upload ảnh
                    $image = $this->uploadImage($_FILES['image']);

                } catch (Exception $e) {

                    $errors[] = $e->getMessage();
                }
            }

            // ===============================
            // Nếu có lỗi
            // ===============================
            if (!empty($errors)) {

                // Load lại categories
                $categories = (new CategoryModel($this->db))->getCategories();

                // Hiển thị lại form
                include 'app/views/product/add.php';

                return;
            }

            // ===============================
            // Thêm sản phẩm vào database
            // ===============================
            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            // ===============================
            // Kiểm tra thêm thành công
            // ===============================
            if ($result) {

                // Chuyển về trang danh sách
                header('Location: /webbanhang/Product');

                exit;

            } else {

                echo "Đã xảy ra lỗi khi thêm sản phẩm.";
            }
        }
    }

    // =====================================================
    // Hiển thị form sửa sản phẩm
    // URL: /Product/edit/id
    // =====================================================
    public function edit($id)
    {
        $this->checkAdmin(); // Chỉ admin mới được phép sửa sản phẩm
        // Lấy sản phẩm theo id
        $product = $this->productModel->getProductById($id);

        // Lấy category
        $categories = (new CategoryModel($this->db))->getCategories();

        // Kiểm tra sản phẩm tồn tại
        if ($product) {

            include 'app/views/product/edit.php';

        } else {

            echo "Không thấy sản phẩm.";
        }
    }

    // =====================================================
    // Xử lý cập nhật sản phẩm
    // =====================================================
    public function update()
    {
        // Kiểm tra POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ===============================
            // Lấy dữ liệu form
            // ===============================
            $id = $_POST['id'];

            $name = trim($_POST['name']);

            $description = trim($_POST['description']);

            $price = trim($_POST['price']);

            $category_id = $_POST['category_id'];

            // ===============================
            // Validate dữ liệu
            // ===============================
            $errors = [];

            if (empty($name)) {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            if (empty($price)) {
                $errors[] = "Giá sản phẩm không được để trống.";
            }

            if (!is_numeric($price) || $price < 0) {
                $errors[] = "Giá sản phẩm không hợp lệ.";
            }

            // ===============================
            // Xử lý ảnh
            // ===============================
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

                try {

                    // Upload ảnh mới
                    $image = $this->uploadImage($_FILES['image']);

                } catch (Exception $e) {

                    $errors[] = $e->getMessage();
                }

            } else {

                // Giữ ảnh cũ
                $image = $_POST['existing_image'];
            }

            // ===============================
            // Nếu có lỗi
            // ===============================
            if (!empty($errors)) {

                $product = $this->productModel->getProductById($id);

                $categories = (new CategoryModel($this->db))->getCategories();

                include 'app/views/product/edit.php';

                return;
            }

            // ===============================
            // Cập nhật database
            // ===============================
            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            // ===============================
            // Kiểm tra cập nhật thành công
            // ===============================
            if ($edit) {

                header('Location: /webbanhang/Product');

                exit;

            } else {

                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    // =====================================================
    // Xóa sản phẩm
    // URL: /Product/delete/id
    // =====================================================
    public function delete($id)
    {
        // Xóa sản phẩm
        if ($this->productModel->deleteProduct($id)) {

            header('Location: /webbanhang/Product');

            exit;

        } else {

            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // =====================================================
    // Hàm upload hình ảnh
    // =====================================================
    private function uploadImage($file)
    {
        // Thư mục upload
        $target_dir = "uploads/";

        // ===============================
        // Nếu chưa có thư mục thì tạo mới
        // ===============================
        if (!is_dir($target_dir)) {

            mkdir($target_dir, 0777, true);
        }

        // ===============================
        // Tạo tên file ngẫu nhiên tránh trùng
        // ===============================
        $fileName = time() . "_" . basename($file["name"]);

        // Đường dẫn file
        $target_file = $target_dir . $fileName;

        // Lấy extension
        $imageFileType = strtolower(
            pathinfo($target_file, PATHINFO_EXTENSION)
        );

        // ===============================
        // Kiểm tra có phải ảnh không
        // ===============================
        $check = getimagesize($file["tmp_name"]);

        if ($check === false) {

            throw new Exception("File không phải là hình ảnh.");
        }

        // ===============================
        // Kiểm tra kích thước file
        // Tối đa 10MB
        // ===============================
        if ($file["size"] > 10 * 1024 * 1024) {

            throw new Exception("Hình ảnh vượt quá 10MB.");
        }

        // ===============================
        // Kiểm tra định dạng file
        // ===============================
        if (
            $imageFileType != "jpg" &&
            $imageFileType != "jpeg" &&
            $imageFileType != "png" &&
            $imageFileType != "gif"
        ) {

            throw new Exception(
                "Chỉ cho phép JPG, JPEG, PNG, GIF."
            );
        }

        // ===============================
        // Upload file
        // ===============================
        if (!move_uploaded_file(
            $file["tmp_name"],
            $target_file
        )) {

            throw new Exception(
                "Có lỗi xảy ra khi upload hình ảnh."
            );
        }

        // Trả về đường dẫn ảnh
        return $target_file;
    }

    // =====================================================
    // Thêm sản phẩm vào giỏ hàng
    // =====================================================
    public function addToCart($id)
    {
        $this->checkLogin(); // Chỉ người dùng đã đăng nhập mới được thêm vào giỏ hàng
        // Lấy sản phẩm
        $product = $this->productModel->getProductById($id);

        // Nếu không tìm thấy
        if (!$product) {

            echo "Không tìm thấy sản phẩm.";

            return;
        }

        // ===============================
        // Nếu chưa có cart
        // ===============================
        if (!isset($_SESSION['cart'])) {

            $_SESSION['cart'] = [];
        }

        // ===============================
        // Nếu sản phẩm đã tồn tại
        // Tăng số lượng
        // ===============================
        if (isset($_SESSION['cart'][$id])) {

            $_SESSION['cart'][$id]['quantity']++;

        } else {

            // ===============================
            // Nếu chưa có thì thêm mới
            // ===============================
            $_SESSION['cart'][$id] = [

                'name' => $product->name,

                'price' => $product->price,

                'quantity' => 1,

                'image' => $product->image
            ];
        }

        // Chuyển tới cart
        header('Location: /webbanhang/Product/cart');

        exit;
    }

    // =====================================================
    // Hiển thị giỏ hàng
    // =====================================================
    public function cart()
    {
        // Lấy cart từ session
        $cartItems = $_SESSION['cart'] ?? [];

        include 'app/views/product/cart.php';
    }

    // =====================================================
    // Hiển thị trang checkout
    // =====================================================
   public function checkout()
{
    // Kiểm tra submit POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Lấy danh sách product được chọn
        $selectedProducts =
            $_POST['selectedProducts'] ?? [];

        // Nếu không chọn sản phẩm
        if (empty($selectedProducts)) {

            echo "Vui lòng chọn sản phẩm.";

            return;
        }

        // Tạo mảng selected cart
        $selectedCart = [];

        foreach ($selectedProducts as $id) {

            // Kiểm tra tồn tại trong session cart
            if (isset($_SESSION['cart'][$id])) {

                $selectedCart[$id] =
                    $_SESSION['cart'][$id];
            }
        }

        // Lưu selected cart
        $_SESSION['selectedCart'] =
            $selectedCart;

        // Truyền sang view checkout
        include 'app/views/product/checkout.php';
    }
}


    // =====================================================
    // Xử lý đặt hàng
    // =====================================================
    public function processCheckout()
    {
        // Kiểm tra POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // ===============================
            // Lấy dữ liệu khách hàng
            // ===============================
            $name = trim($_POST['name']);

            $phone = trim($_POST['phone']);

            $address = trim($_POST['address']);

            // ===============================
            // Kiểm tra cart
            // ===============================
            if (
                !isset($_SESSION['selectedCart']) ||
                empty($_SESSION['selectedCart'])
            ) {

                echo "Giỏ hàng trống.";

                return;
            }

            $this->db->beginTransaction();

            try {

                // ===============================
                // Lưu đơn hàng
                // ===============================
                $query = "
                    INSERT INTO orders(name, phone, address)
                    VALUES(:name, :phone, :address)
                ";

                $stmt = $this->db->prepare($query);

                $stmt->bindParam(':name', $name);

                $stmt->bindParam(':phone', $phone);

                $stmt->bindParam(':address', $address);

                $stmt->execute();

                // Lấy order id
                $order_id = $this->db->lastInsertId();

                // ===============================
                // Lưu chi tiết đơn hàng
                // ===============================
                $cart = $_SESSION['selectedCart'] ;

                foreach ($cart as $product_id => $item) {

                    $query = "
                        INSERT INTO order_details(
                            order_id,
                            product_id,
                            quantity,
                            price
                        )
                        VALUES(
                            :order_id,
                            :product_id,
                            :quantity,
                            :price
                        )
                    ";

                    $stmt = $this->db->prepare($query);

                    $stmt->bindParam(':order_id', $order_id);

                    $stmt->bindParam(':product_id', $product_id);

                    $stmt->bindParam(':quantity', $item['quantity']);

                    $stmt->bindParam(':price', $item['price']);

                    $stmt->execute();
                }

                // ===============================
// Xóa các sản phẩm đã thanh toán
// khỏi giỏ hàng chính
// ===============================
foreach ($cart as $product_id => $item) {

    unset($_SESSION['cart'][$product_id]);
}

// ===============================
// Xóa selected cart
// ===============================
unset($_SESSION['selectedCart']);
                // ===============================
                // Commit transaction
                // ===============================
                $this->db->commit();

                // Chuyển hướng
                header(
                    'Location: /webbanhang/Product/orderConfirmation'
                );

                exit;

            } catch (Exception $e) {

                // ===============================
                // Rollback nếu lỗi
                // ===============================
                $this->db->rollBack();

                echo "Đã xảy ra lỗi: " . $e->getMessage();
            }
        }
    }
    // =====================================================
// Tăng số lượng sản phẩm trong giỏ hàng
// =====================================================
public function increase($id)
{
    // Kiểm tra sản phẩm tồn tại trong cart
    if (isset($_SESSION['cart'][$id])) {

        // Tăng số lượng
        $_SESSION['cart'][$id]['quantity']++;
    }

    // Quay lại cart
    header('Location: /webbanhang/Product/cart');

    exit;
}
    // =====================================================
// Giảm số lượng sản phẩm trong giỏ hàng
// =====================================================
public function decrease($id)
{
    // Kiểm tra sản phẩm tồn tại
    if (isset($_SESSION['cart'][$id])) {

        // Giảm số lượng
        $_SESSION['cart'][$id]['quantity']--;

        // Nếu số lượng <= 0 thì xóa
        if ($_SESSION['cart'][$id]['quantity'] <= 0) {

            unset($_SESSION['cart'][$id]);
        }
    }

    // Quay lại cart
    header('Location: /webbanhang/Product/cart');

    exit;
}
    // =====================================================
// Xóa sản phẩm khỏi giỏ hàng
// =====================================================
public function remove($id)
{
    // Kiểm tra tồn tại
    if (isset($_SESSION['cart'][$id])) {

        // Xóa sản phẩm
        unset($_SESSION['cart'][$id]);
    }

    // Quay lại cart
    header('Location: /webbanhang/Product/cart');

    exit;
}

// =====================================================
// Xóa toàn bộ giỏ hàng
// =====================================================
public function clearCart()
{
    // Xóa session cart
    unset($_SESSION['cart']);

    // Quay lại cart
    header('Location: /webbanhang/Product/cart');

    exit;
}

    // Trang xác nhận đặt hàng thành công
    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>