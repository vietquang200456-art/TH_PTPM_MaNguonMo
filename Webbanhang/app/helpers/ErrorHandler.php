<?php

class ErrorHandler
{
    /**
     * Log error message to file
     */
    public static function log(string $message, string $level = 'ERROR'): void
    {
        $logDir = 'logs/';
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message\n";

        error_log($logMessage, 3, $logFile);
    }

    /**
     * Display 404 error page
     */
    public static function show404(): void
    {
        http_response_code(404);
        echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-1 text-danger">404</h1>
            <h2>Không tìm thấy trang</h2>
            <p class="text-muted">Trang bạn tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
            <a href="/webbanhang/product" class="btn btn-primary mt-3">Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>';
        exit;
    }

    /**
     * Display 500 error page
     */
    public static function show500(string $message = ''): void
    {
        http_response_code(500);
        echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi máy chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-1 text-danger">500</h1>
            <h2>Lỗi máy chủ nội bộ</h2>
            <p class="text-muted">Có lỗi xảy ra, vui lòng thử lại sau.</p>';
        
        if (!empty($message) && defined('DEBUG_MODE') && DEBUG_MODE) {
            echo '<p class="text-danger small mt-3">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        
        echo '<a href="/webbanhang/product" class="btn btn-primary mt-3">Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>';
        exit;
    }
}
