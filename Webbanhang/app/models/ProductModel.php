<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, c.name as category_name
                    FROM " . $this->table_name . " p
                    LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getProductById($id)
{
    $query = "
        SELECT 
            *,
            c.name AS category_name
        FROM " . $this->table_name . " p
        LEFT JOIN category c
            ON p.category_id = c.id
        WHERE p.id = :id
    ";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_OBJ);
}

    public function addProduct($name, $description, $price, $category_id)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu chuỗi text
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        // Ép kiểu số cho đúng bản chất dữ liệu
        $price = (float)$price;
        $category_id = (int)$category_id;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id)
    {
        $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu chuỗi text
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        // Ép kiểu số
        $id = (int)$id;
        $price = (float)$price;
        $category_id = (int)$category_id;

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getFilteredProducts($keyword = '',$category_id = '')
    {
    $query = "
        SELECT
            p.*,
            c.name AS category_name
        FROM product p
        LEFT JOIN category c
            ON p.category_id = c.id
        WHERE 1
    ";

    // Tìm kiếm tên
    if (!empty($keyword)) {

        $query .= "
            AND p.name LIKE :keyword
        ";
    }

    // Lọc category
    if (!empty($category_id)) {

        $query .= "
            AND p.category_id = :category_id
        ";
    }

    $query .= "
        ORDER BY p.id DESC
    ";

    $stmt = $this->conn->prepare($query);

    // Bind keyword
    if (!empty($keyword)) {

        $search = "%$keyword%";

        $stmt->bindParam(
            ':keyword',
            $search
        );
    }

    // Bind category
    if (!empty($category_id)) {

        $stmt->bindParam(
            ':category_id',
            $category_id
        );
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>