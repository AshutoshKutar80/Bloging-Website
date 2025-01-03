<?php
session_start();
include("../conn/conn.php");

// Ensure the user is logged in and form submission is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $level = $_POST['level'] ?? 'Beginner';
    $profile_pic = $_FILES['profile-pic'] ?? null;

    // Validate the level input
    $allowed_levels = ['Beginner', 'Intermediate', 'Advanced'];
    if (!in_array($level, $allowed_levels)) {
        echo "Invalid level selected.";
        exit();
    }

    // Initialize query and parameters
    $update_query = "UPDATE users SET level = ?";
    $params = [$level];
    $types = "s"; // Prepared statement types

    // Handle profile picture upload
    if (!empty($profile_pic['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($profile_pic['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_extensions)) {
            echo "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
            exit();
        }

        $upload_dir = '../image/';
        $file_name = uniqid("profile_", true) . '.' . $file_ext; // Unique file name
        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($profile_pic['tmp_name'], $upload_path)) {
            $public_path = '/social_network/image/' . $file_name; // Relative path for HTTP access
            $update_query .= ", profile_pic = ?";
            $params[] = $public_path;
            $types .= "s";
        } else {
            echo "Failed to upload the profile picture.";
            exit();
        }
    }

    $update_query .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    // Prepare and execute the statement
    $stmt = mysqli_prepare($conn, $update_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (mysqli_stmt_execute($stmt)) {
            echo "Profile updated successfully!";
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Unauthorized access or missing data.";
}
?>