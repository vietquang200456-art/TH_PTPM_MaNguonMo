<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getCategories()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function addCategory($name, $description)
{
    $query = "INSERT INTO category(name, description)
              VALUES(:name, :description)";

    $stmt = $this->conn->prepare($query);

    $name = htmlspecialchars(strip_tags($name));
    $description = htmlspecialchars(strip_tags($description));

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    return $stmt->execute();
}

    public function getCategoryById($id){
        $query = "SELECT * from category where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function deleteCategory($id)
{
    $query = "DELETE FROM category WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $id);

    return $stmt->execute();
}

    public function updateCategory($id, $name, $description)
{
    $query = "UPDATE category
              SET name = :name, description = :description
              WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $name = htmlspecialchars(strip_tags($name));
    $description = htmlspecialchars(strip_tags($description));
    $id = htmlspecialchars(strip_tags($id));

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);

    return $stmt->execute();

}
}
?>