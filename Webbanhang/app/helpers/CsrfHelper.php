<?php

class CsrfHelper
{
    /**
     * Tạo CSRF token và lưu vào session
     */
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Lấy CSRF token hiện tại
     */
    public static function getToken(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    /**
     * Kiểm tra CSRF token có hợp lệ không
     */
    public static function verifyToken(string $token): bool
    {
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        // So sánh token bằng hash_equals để tránh timing attack
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Render CSRF token field cho form
     */
    public static function renderTokenField(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '" />';
    }
}
