<?php
session_start();
include("../conn/conn.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to access the dashboard.'); window.location.href = 'Login.php';</script>";
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('User not found.'); window.location.href = 'Login.php';</script>";
    exit();
}

// Fetch posts for the user
$post_sql = "SELECT * FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
$post_result = mysqli_query($conn, $post_sql);
$posts = mysqli_fetch_all($post_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/update.css">
</head>

<body>
    <div class="main">

        <div class="profile">
            <h1 class="heading"> Social Network</h1>
            <div class="dashboard-container">
                <h1>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h1>
                <div class="profile">
                    <img id="profile-pic" src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" />
                    <p><?php echo $user['full_name']; ?></p>
                    <p><?php  echo $user['email']; ?></p>
                    <p><span id="user-level"><?php echo $user['level'] ?? 'Beginner'; ?></span></p>
                </div>
                <button class="btn" id="share-btn">Share</button>
                <button class="btn" id="edit-btn">Edit Profile</button>
                <button class="btn" id="logoutBtn">Logout</button> <!-- Logout button -->

                <!-- Edit Modal -->
                <div id="edit-modal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Edit Profile</h2>
                        <form id="edit-form" enctype="multipart/form-data">
                            <label for="profile-pic-input">Change Profile Picture:</label>
                            <input type="file" id="profile-pic-input" name="profile-pic">
                            <label for="level">Change Level:</label>
                            <select id="level" name="level">
                                <option value="Beginner" <?php echo $user['level'] === 'Beginner' ? 'selected' : ''; ?>>
                                    Beginner</option>
                                <option value="Intermediate"
                                    <?php echo $user['level'] === 'Intermediate' ? 'selected' : ''; ?>>Intermediate
                                </option>
                                <option value="Advanced" <?php echo $user['level'] === 'Advanced' ? 'selected' : ''; ?>>
                                    Advanced</option>
                            </select>
                            <button type="submit">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="post">
            <!-- Post Creation Form -->
            <div class="post-form">
                <h2>Add Post</h2>
                <div class="add_post">
                    <form id="post-form" enctype="multipart/form-data">
                        <div class="post">
                            <textarea name="content" id="post-content" placeholder="What's on your mind?"
                                required></textarea><br>
                            <img src="../assests/add.png" alt="Pic Here" class="post_pic" id="post-pic" />
                            <input type="file" name="image" id="post-image" accept="image/jpg, image/png, image/jpeg"
                                style="display: none;"><br>
                        </div>
                        <div class="sub">
                            <button type="submit">Post</button>
                            <label for="post-image"><img src="../assests/image.png" alt="" class="icon">Add
                                Image</label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Displaying Posts -->
            <div class="posts">
                <h3>Your Posts</h3>
                <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                <div class="post" id="post-<?php echo $post['id']; ?>">
                    <!-- Displaying the User's Profile Image -->
                    <div class="post-header">

                        <img src="<?php echo $user['profile_pic']; ?>" alt="User Profile" class="user-profile-pic">
                        <!-- This will print the image path -->

                        <div class="title">
                            <p><?php echo $post['content']; ?></p>
                            <h6>Posted on: <?php echo $post['created_at']; ?></h6>
                        </div>

                    </div>
                    <?php if ($post['image']): ?>
                    <img src="/social_network/<?php echo $post['image']; ?>" alt="Post Image"
                        style="width: 360px; height: 240px; object-fit: cover;">
                    <?php endif; ?>
                    <p>
                        <label class="like-btn" data-post-id="<?php echo $post['id']; ?>">
                            <img src="../assests/like.png" class="icon"> Like <?php echo $post['likes']; ?>
                        </label>
                        <label class="dislike-btn" data-post-id="<?php echo $post['id']; ?>">
                            <img src="../assests/dont-like.png" class="icon"> Dislike
                            <?php echo $post['dislikes']; ?>
                        </label>
                        <button class="delete-btn" data-post-id="<?php echo $post['id']; ?>">Delete</button>
                        <!-- Delete button -->
                    </p>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>No posts to display.</p>
                <?php endif; ?>
            </div>

        </div>

    </div>


    <script>
    const profile_pic = document.getElementById("post-pic");
    const path = document.getElementById("post-image");
    path.onchange = function() {
        if (path.files[0]) {
            profile_pic.src = URL.createObjectURL(path.files[0]);
        }
    };

    $(document).ready(function() {
        // perform like and dislike functionality
        // Like button functionality
        $('.like-btn').on('click', function() {
            const postId = $(this).data('post-id');
            $.ajax({
                url: 'update_reaction.php',
                type: 'POST',
                data: {
                    action: 'like',
                    post_id: postId
                },
                success: function(response) {
                    // Update the like count on success
                    const data = JSON.parse(response);
                    $('#post-' + postId + ' .dislike-btn').html(
                        `<img src="../assests/dont-like.png" class="icon">Dislike (${data.dislikes})`
                    );
                    $(`#post-${postId} .like-btn`).html(
                        `<img src="../assests/like.png" class="icon"> Like (${data.likes})`
                    );
                },
                error: function() {
                    alert('An error occurred while liking the post.');
                }
            });
        });

        // Dislike button functionality
        $('.dislike-btn').on('click', function() {
            const postId = $(this).data('post-id');
            $.ajax({
                url: 'update_reaction.php',
                type: 'POST',
                data: {
                    action: 'dislike',
                    post_id: postId
                },
                success: function(response) {
                    // Update the dislike count on success
                    const data = JSON.parse(response);


                    $('#post-' + postId + ' .dislike-btn').html(
                        `<img src="../assests/dont-like.png" class="icon">Dislike (${data.dislikes})`
                    );
                    $(`#post-${postId} .like-btn`).html(
                        `<img src="../assests/like.png" class="icon"> Like (${data.likes})`
                    );
                },
                error: function() {
                    alert('An error occurred while disliking the post.');
                }
            });
        });
    });


    $(document).ready(function() {
        const modal = $('#edit-modal');
        const closeModal = $('.close-btn');

        $('#edit-btn').on('click', function() {
            modal.show();
        });

        closeModal.on('click', function() {
            modal.hide();
        });

        $(window).on('click', function(e) {
            if ($(e.target).is(modal)) {
                modal.hide();
            }
        });

        // Handle profile update form submission using AJAX
        $('#edit-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'update_profile');

            $.ajax({
                url: 'update_profile.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function() {
                    alert('An error occurred while updating your profile.');
                }
            });
        });

        // Handle post form submission using AJAX
        $('#post-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create_post');

            $.ajax({
                url: 'create_post.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                    location.reload(); // Reload the page to show new post
                },
                error: function() {
                    alert('An error occurred while creating your post.');
                }
            });
        });

        $('#share-btn').on('click', function() {
            alert('Share feature coming soon!');
        });

        // Logout functionality
        $('#logoutBtn').on('click', function() {
            $.ajax({
                url: 'logout.php',
                type: 'POST',
                success: function() {
                    window.location.href = 'Login.php';
                },
                error: function() {
                    alert('An error occurred while logging out.');
                }
            });
        });



        // Handle delete post functionality
        $('.delete-btn').on('click', function() {
            const postId = $(this).data('post-id');
            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: 'delete_post.php', // Create this script to handle deletion
                    type: 'POST',
                    data: {
                        post_id: postId
                    },
                    success: function(response) {
                        if (response.trim() === 'success') {
                            alert('Post deleted successfully!');
                            $('#post-' + postId).remove(); // Remove the post from the DOM
                        } else {
                            alert('Failed to delete the post: ' + response);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the post.');
                    }
                });
            }
        });

    });
    </script>

</body>

</html>