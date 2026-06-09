<?php

class FileUploadHelper
{
    /**
     * Allowed MIME types for images
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Allowed file extensions
     */
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * Maximum file size (10MB)
     */
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Upload directory
     */
    private const UPLOAD_DIR = 'uploads/';

    /**
     * Upload image file with full validation
     * 
     * @param array $file File from $_FILES
     * @return string Path to uploaded file
     * @throws Exception If validation fails
     */
    public static function uploadImage(array $file): string
    {
        // Validate file upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception(self::getUploadErrorMessage($file['error']));
        }

        // Validate file is actually uploaded
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new Exception('File không hợp lệ (không phải file upload).');
        }

        // Check if upload directory exists, create if not
        if (!is_dir(self::UPLOAD_DIR)) {
            if (!mkdir(self::UPLOAD_DIR, 0755, true)) {
                throw new Exception('Không thể tạo thư mục upload.');
            }
        }

        // Validate file is image
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new Exception('File không phải là hình ảnh hợp lệ.');
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new Exception('Định dạng ảnh không được hỗ trợ. Hãy sử dụng JPG, PNG, GIF hoặc WEBP.');
        }

        // Validate file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, self::ALLOWED_EXTENSIONS, true)) {
            throw new Exception('Phần mở rộng file không được phép.');
        }

        // Validate file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new Exception('Hình ảnh vượt quá 10MB.');
        }

        // Generate secure filename
        $fileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
        $targetFile = self::UPLOAD_DIR . $fileName;

        // Check for directory traversal attack
        $realPath = realpath(dirname($targetFile));
        if ($realPath === false || strpos($realPath, realpath(self::UPLOAD_DIR)) !== 0) {
            throw new Exception('Đường dẫn file không hợp lệ.');
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception('Có lỗi xảy ra khi upload hình ảnh.');
        }

        // Set file permissions
        chmod($targetFile, 0644);

        return $targetFile;
    }

    /**
     * Get human-readable upload error message
     */
    private static function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'Hình ảnh vượt quá giới hạn kích thước cho phép trên server.',
            UPLOAD_ERR_FORM_SIZE => 'Hình ảnh vượt quá giới hạn kích thước cho phép trong form.',
            UPLOAD_ERR_PARTIAL => 'Hình ảnh chỉ được upload một phần.',
            UPLOAD_ERR_NO_FILE => 'Không có file nào được chọn.',
            UPLOAD_ERR_NO_TMP_DIR => 'Thư mục upload không tồn tại.',
            UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file.',
            UPLOAD_ERR_EXTENSION => 'Upload bị chặn bởi extension PHP.',
            default => 'Lỗi upload không xác định.',
        };
    }

    /**
     * Delete uploaded file
     */
    public static function deleteFile(string $filePath): bool
    {
        if (empty($filePath) || !file_exists($filePath)) {
            return false;
        }

        // Security check
        $realPath = realpath($filePath);
        if ($realPath === false || strpos($realPath, realpath(self::UPLOAD_DIR)) !== 0) {
            return false;
        }

        return unlink($filePath);
    }
}
