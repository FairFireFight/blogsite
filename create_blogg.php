<?php
    require_once "database/user_model.php";
    require_once "database/blog_model.php";

    session_start();

    if (!isset($_SESSION['authenticated'])) {
        header('Location: login.php');
        exit;
    }

    $user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'common/bootstraplink.html'?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Blogg</title>
    </head>
    <body>
        <header class="px-3 py-1 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold">
                            <a class="text-light text-decoration-none" href="home.php">Bloggers</a>
                        </h2>
                    </div>

                    <!-- Spacer col -->
                    <div class="col-6 p-0"></div>

                    <!-- Profile / Buttons -->
                    <div class="w-100 d-block d-md-none"></div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="btn-group">
                                <button class="btn dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="rounded-circle" src="uploads/images/profile_pictures/default.jpg" style="max-width: 50px"/>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="profile?id=<?= $user->id ?>">My Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-md my-4">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h2>Create Blogg</h2>
                        </div>
                        <div class="card-body">
                            <?php
                                $title_valid = true;

                                $success = true;
                                if (isset($_POST['submit'])) {
                                    $author = $_SESSION['user']->id;

                                    $title = $_POST['title'];
                                    $content = $_POST['content'];
                                    
                                    $title_valid = strlen($title) >= 5;

                                    if ($title_valid) {
                                        $id = BlogModel::create_blog($author, $title, $content);

                                        if (!empty($_FILES["images"]['name'][0])) {
                                            $uploadOk = 1;
                                            $target_dir = "uploads/images/content/$id";

                                            if (!file_exists($target_dir)) {
                                                mkdir($target_dir, 0777, true);
                                            }

                                            print_r($_FILES['images']);

                                            foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                                                $targetFile = "$target_dir/$id" . basename($_FILES["images"]["name"][$key]);
                                                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                                                if ($_FILES["images"]["size"][$key] > 25000000) {
                                                    echo "Exceeded max size";
                                                    $uploadOk = 0;
                                                    break;
                                                }
    
                                                // Only allow certain file formats
                                                $allowedFormats = ["jpg", "jpeg", "png", "gif"];
                                                if (!in_array($imageFileType, $allowedFormats)) {
                                                    echo "Format not allowed";
                                                    $uploadOk = 0;
                                                    break;
                                                }

                                                if ($uploadOk) {
                                                    move_uploaded_file($tmp_name, $targetFile);
                                                }
                                            }
                                        }

                                        if ($id) {
                                            header("Location: blogg.php?id=$id");
                                            exit;
                                        } else {
                                            $success = false;
                                        }
                                    }
                                }
                            ?>

                            <form method="POST" action="create_blogg.php" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="title-input" class="form-label">Title</label>
                                    <input id="title-input" name="title" type="text" class="form-control" placeholder="An interesting title..." min="5" required>
                                    <?php
                                        if (!$title_valid) {
                                            echo 
                                            '<div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                                Title must be at least 5 characters long.
                                                <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                            </div>';
                                        }
                                    ?>
                                </div>
                                <div class="mb-3">
                                    <label for="content-input" class="form-label">Content</label>
                                    <textarea id="content-input" name="content" class="form-control" placeholder="Text (Optional)" style="height: 30vh"></textarea>
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="25000000" />
                                    <label for="file" class="form-label">Choose Images (Up to 4 allowed)</label>
                                    <input class="form-control" name="images[]" type="file" 
                                    id="file" multiple accept=".jpg, .jpeg, .png">
                                </div>
                                <hr/>
                                <div class="d-flex justify-content-end w-100">
                                    <a class="mx-1 btn btn-secondary" href="home.php">Cancel</a>
                                    <input type="submit" class="ms-1 btn btn-primary" name="submit" value="Blogg it!"/>
                                </div>
                                <?php
                                    if (!$success) {
                                        echo 
                                        '<div class="alert alert-danger alert-dismissible p-2 mt-2" role="alert">
                                            Something went wrong, please try again later.
                                            <button class="btn-close m-1 p-2" aria-label="Close" data-bs-dismiss="alert"></button>
                                        </div>';
                                    }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>