<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 0);
    
    session_start();
    
    if (!isset($_SESSION['_created'])) {
        $_SESSION['_created'] = time();
    } else if (time() - $_SESSION['_created'] > 3600) {
        session_destroy();
        session_start();
        $_SESSION['_created'] = time();
    }
    
    if (!isset($_SESSION['_ip'])) {
        $_SESSION['_ip'] = $_SERVER['REMOTE_ADDR'];
    } else if ($_SESSION['_ip'] !== $_SERVER['REMOTE_ADDR']) {
        session_destroy();
        session_start();
        $_SESSION['_ip'] = $_SERVER['REMOTE_ADDR'];
    }
}

function generate_csrf_token() {
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verify_csrf_token($token) {
    if (!isset($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
        return false;
    }
    return true;
}

function get_csrf_field() {
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(generate_csrf_token()) . '">';
}
