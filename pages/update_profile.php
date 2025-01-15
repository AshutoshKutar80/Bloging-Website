<?php
session_start();
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $level = $_POST['level'];
    $profile_pic = $_FILES['profile-pic'];

    // Validate level
    if ($level != 'Beginner' && $level != 'Intermediate' && $level != 'Advanced') {
        echo "Invalid level.";
        exit();
    }

    // File upload
    if (!empty($profile_pic['name'])) {
        $ext = pathinfo($profile_pic['name'], PATHINFO_EXTENSION);
        if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png') {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            exit();
        }

        $upload_dir = '../image/';
        $file_name = uniqid() . '.' . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($profile_pic['tmp_name'], $upload_path)) {
            echo "File upload failed.";
            exit();
        }

        $profile_pic_path = '/social_network/image/' . $file_name;
    } else {
        $profile_pic_path = null;
    }

    // Update the database
    $sql = "UPDATE users SET level = '$level'";
    if ($profile_pic_path) {
        $sql .= ", profile_pic = '$profile_pic_path'";
    }
    $sql .= " WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        echo "Profile updated!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Unauthorized access.";
}
?>