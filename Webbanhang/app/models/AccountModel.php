<?php

class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy tài khoản theo username
     */
    public function getAccountByUsername(string $username): ?object
    {
        $query = "SELECT * FROM account WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }
    /**
     * Tạo tài khoản mới
     */
    public function save(string $username, string $fullName, string $password, string $role = "user"): bool
    {
        $query = "INSERT INTO account (username, fullname, password, role) 
                  VALUES (:username, :fullname, :password, :role)";
        $stmt = $this->conn->prepare($query);

        // Sanitize data
        $username = htmlspecialchars(strip_tags($username), ENT_QUOTES, 'UTF-8');
        $fullName = htmlspecialchars(strip_tags($fullName), ENT_QUOTES, 'UTF-8');

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':fullname', $fullName);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }
}