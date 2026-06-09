<?php

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        // Khởi tạo kết nối Database và gán vào thuộc tính $db
        $this->db = (new Database())->getConnection();
        // Khởi tạo đối tượng ProductModel truyền vào kết nối DB
        $this->productModel = new ProductModel($this->db);
    }

    /**
     * Lấy danh sách toàn bộ sản phẩm
     * GET /api/products
     */
    public function index()
    {
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }

    /**
     * Lấy thông tin chi tiết một sản phẩm theo ID
     * GET /api/products/{id}
     */
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);

        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404); // Trả về lỗi không tìm thấy
            echo json_encode(['message' => 'Product not found']);
        }
    }

    /**
     * Thêm mới một sản phẩm
     * POST /api/products
     */
    public function store()
    {
        header('Content-Type: application/json');
        
        // Đọc dữ liệu JSON từ request body gửi lên
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Sử dụng toán tử ?? để tránh lỗi nếu thiếu key trong JSON
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        // Gọi Model để thêm sản phẩm vào DB (tham số cuối 'null' có thể là ảnh)
        $result = $this->productModel->addProduct($name, $description, $price, $category_id, null);

        if (is_array($result)) {
            http_response_code(400); // Lỗi dữ liệu đầu vào (Validation)
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201); // Tạo mới thành công
            echo json_encode(['message' => 'Product created successfully']);
        }
    }

    /**
     * Cập nhật thông tin sản phẩm theo ID
     * PUT/PATCH /api/products/{id}
     */
    public function update($id)
    {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, null);
        
        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400); // Cập nhật thất bại
            echo json_encode(['message' => 'Product update failed']);
        }
    }

    /**
     * Xóa sản phẩm theo ID
     * DELETE /api/products/{id}
     */
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->productModel->deleteProduct($id);
        
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400); // Xóa thất bại
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }
}