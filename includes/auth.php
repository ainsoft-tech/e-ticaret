<?php
session_start();

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('admin/login.php');
    }
}

function loginUser($user_id, $username, $is_admin = false) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    if ($is_admin) {
        $_SESSION['admin'] = true;
    }
}

function logoutUser() {
    session_destroy();
    redirect('index.php');
}
?>

