<?php
session_start();
include("../conn/conn.php");

// Ensure the user is logged in and the post ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);

    // Check if the post belongs to the logged-in user
    $query = "SELECT image FROM posts WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        // Delete the image file if it exists
        if (!empty($post['image'])) {
            $image_path = '../' . $post['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete the post from the database
        $delete_query = "DELETE FROM posts WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $post_id);
        if (mysqli_stmt_execute($delete_stmt)) {
            echo 'success';
        } else {
            echo 'Error: ' . mysqli_stmt_error($delete_stmt);
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        echo 'Post not found or you do not have permission to delete it.';
    }

    mysqli_stmt_close($stmt);
} else {
    echo 'Unauthorized access.';
}
?>