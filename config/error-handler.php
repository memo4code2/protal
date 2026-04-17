<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

function log_error($error_message) {
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $message = "[$timestamp] IP: $ip | Error: $error_message\n";
    error_log($message);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    log_error("$errstr in $errfile on line $errline");
    
    if ($errno === E_WARNING || $errno === E_NOTICE || $errno === E_DEPRECATED) {
        return true;
    }
    
    if ($errno === E_ERROR || $errno === E_PARSE) {
        http_response_code(500);
        die('<div style="padding:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:4px;">An error occurred. Our team has been notified.</div>');
    }
    
    return false;
});

set_exception_handler(function($exception) {
    log_error('Exception: ' . $exception->getMessage() . ' at ' . $exception->getFile() . ':' . $exception->getLine());
    http_response_code(500);
    die('<div style="padding:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:4px;">An error occurred. Our team has been notified.</div>');
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null) {
        log_error('Fatal: ' . $error['message']);
    }
});
