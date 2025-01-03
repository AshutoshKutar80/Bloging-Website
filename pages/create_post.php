<?php
session_start();
include("../conn/conn.php");

if (isset($_POST['action']) && $_POST['action'] == 'create_post') {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = null;

    // Handle image upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        
        $image = 'image/' . basename($_FILES['image']['name']);  // Store in the uploads directory
        move_uploaded_file($_FILES['image']['tmp_name'], "C:/xampp/htdocs/social_network/image/" . basename($_FILES['image']['name']));

    }

    // Insert post into database
    $sql = "INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$image')";
    if (mysqli_query($conn, $sql)) {
        echo "Post created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>