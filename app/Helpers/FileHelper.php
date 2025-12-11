<?php

/**
 * Format bytes to readable format
 */
if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $decimals = 2)
    {
        if ($bytes === 0) return '0 Bytes';
        
        $k = 1024;
        $dm = $decimals < 0 ? 0 : $decimals;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        $i = floor(log($bytes, $k));
        
        return round($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
    }
}

/**
 * Get file extension from filename
 */
if (!function_exists('getFileExtension')) {
    function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
}

/**
 * Get file icon class based on extension
 */
if (!function_exists('getFileIcon')) {
    function getFileIcon($filename)
    {
        $extension = strtolower(getFileExtension($filename));
        
        $iconMap = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
            '7z' => 'fa-file-archive',
        ];
        
        return $iconMap[$extension] ?? 'fa-file';
    }
}
