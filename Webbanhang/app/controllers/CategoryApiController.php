<?php

require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        // Khởi tạo kết nối cơ sở dữ liệu
        $this->db = (new Database())->getConnection();
        // Khởi tạo đối tượng CategoryModel và truyền kết nối DB vào
        $this->categoryModel = new CategoryModel($this->db);
    }

    /**
     * Lấy danh sách danh mục
     * GET /api/categories
     */
    public function index()
    {
        // Định dạng dữ liệu trả về là JSON
        header('Content-Type: application/json');
        
        // Gọi model để lấy danh sách danh mục
        $categories = $this->categoryModel->getCategories();
        
        // Chuyển đổi mảng PHP thành chuỗi JSON và xuất ra client
        echo json_encode($categories);
    }
}