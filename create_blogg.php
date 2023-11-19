<?php
    require_once "database/user_model.php";
    require_once "database/blog_model.php";

    session_start();

    if (!isset($_SESSION['authenticated'])) {
        header('Location: login.php');
        exit;
    }


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
        <header class="p-3 sticky-top text-light bg-dark">
            <div class="container-md">
                <div class="row justify-content-between align-items-center">
                    <!-- Site name col -->
                    <div class="col-3 p-0">
                        <h2 class="p-0 fw-semibold"><a class="text-light text-decoration-none" href="home.php">Bloggers</a></h2>
                    </div>

                    <!-- Spacer col -->
                    <div class="col-6 p-0"></div>

                    <!-- Profile / Buttons -->
                    <div class="w-100 d-block d-md-none"></div>
                    <div class="col-md-3">
                        <div class="row justify-content-end">
                            <a class="col-md-3 btn mx-md-1 mx-0 mt-md-0 mt-1 btn-outline-secondary" href="login.php">Login</a>
                            <a class="col-md-3 btn mt-md-0 mt-1 btn-primary" href="register.php">Sign up</a>
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
                                if (isset($_POST['submit'])) {
                                    $author = $_SESSION['user']->id;

                                    $title = mysqli_real_escape_string($conn, $_POST['title']);
                                    $content = mysqli_real_escape_string($conn, $_POST['content']);
                                    
                                    $title_valid = strlen($title) > 5;

                                    if ($title_valid) {
                                        $id = BlogModel::create_blog($author, $title, $content);
                                        header("Location: blogg.php?id=$id");
                                        exit;
                                    }
                                }
                            ?>

                            <form method="POST" action="create_blogg.php">
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
                                    <textarea id="content-input" name="content" class="form-control" placeholder="Text (Optional)"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="file" class="form-label">Choose Image(s)</label>
                                    <input class="form-control" name="images" type="file" id="file" multiple>
                                </div>
                                <hr/>
                                <div class="d-flex justify-content-end w-100">
                                    <a class="mx-1 btn btn-secondary" href="home.php">Cancel</a>
                                    <input type="submit" class="ms-1 btn btn-primary" name="submit" value="Blogg it!"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>