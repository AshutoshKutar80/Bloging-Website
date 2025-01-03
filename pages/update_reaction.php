<?php
session_start();
include("../conn/conn.php");

if (isset($_POST['action']) && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    // Check if post exists
    $sql = "SELECT * FROM posts WHERE id = '$post_id'";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    if ($post) {
        // Handle like action
        if ($action == 'like') {
            $new_likes = $post['likes'] + 1;
            $sql = "UPDATE posts SET likes = '$new_likes' WHERE id = '$post_id'";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['likes' => $new_likes, 'dislikes' => $post['dislikes']]);
            } else {
                echo json_encode(['error' => 'Failed to update likes']);
            }
        }
        // Handle dislike action
        elseif ($action == 'dislike') {
            $new_dislikes = $post['dislikes'] + 1;
            $sql = "UPDATE posts SET dislikes = '$new_dislikes' WHERE id = '$post_id'";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(['dislikes' => $new_dislikes, 'likes' => $post['likes']]);
            } else {
                echo json_encode(['error' => 'Failed to update dislikes']);
            }
        }
    } else {
        echo json_encode(['error' => 'Post not found']);
    }
}
?>