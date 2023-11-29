function LoadBlogs() {
    let bottom = document.getElementById("container-bottom");

    contentContainer.innerHTML = '';
    bottom.innerHTML = "<div class='spinner-border'></div>";

    // Make the request and get the data for the page
    $.ajax({
        url: 'libraries/blog_manager.php',
        type: 'GET',
        data: {user_id: userId},
        success: function (response) {
            let jsonArray = JSON.parse(response);

            if (jsonArray.length == 0) {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>You haven't made any Bloggs yet.</div>";
            } else {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>That's all of your Bloggs</div>"
            }
            
            jsonArray.forEach(blog => {
                let imageCount = blog.images.length;

                if (blog.content === null) {
                    blog.content = '';
                }


                // yes this is probably THE worst way you could do this.
                let imageContainer = `
                    <div class="container px-md-5">
                        <div class="row">
                            ${imageCount >= 1 ? '<img class="blog-image col rounded m-1 p-0" src="uploads/images/content/' + blog.id + '/' + blog.images[0] + '"/>' : ""}
                            ${imageCount >= 2 ? '<img class="blog-image col rounded m-1 p-0" src="uploads/images/content/' + blog.id + '/' + blog.images[1] + '"/>' : ""}
                        </div>
                        <div class="row"> 
                            ${imageCount >= 3 ? '<img class="blog-image col rounded m-1 p-0" src="uploads/images/content/' + blog.id + '/' + blog.images[2] + '"/>' : ""}
                            ${imageCount >= 4 ? '<img class="blog-image col rounded m-1 p-0" src="uploads/images/content/' + blog.id + '/' + blog.images[3] + '"/>' : ""}
                        </div>
                    </div>`;

                let blogHtmlModel = `
                    <div class="card my-3"> 
                        <a class="card-body px-md-5 text-decoration-none" href="/blogg.php?id=${blog.id}">
                            <h3 class="card-title fw-semibold ${blog.content == '' ? '' : 'border-bottom pb-2'}">${blog.title}</h3>
                            <p id='${blog.id}'class="card-text fs-5">${blog.content.substr(0, 512)}</p>
                            ${imageCount > 0 ? imageContainer : ""}
                        </a>
                        <div class="card-footer px-md-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-start">
                                    <div id="${blog.id}" class="btn ${blog.liked ? "btn-danger" : "btn-outline-danger"} fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blog.likes}</div>
                                    <a class="btn btn-outline-secondary fw-semibold me-2 d-inline" href="/blogg.php?id=${blog.id}">💬 ${blog.comments}</a>
                                </div>
                                <p class="align-middle text-end m-0">${timeSince(new Date(blog.blog_time))}</p>
                            </div>
                        </div>
                    </div>`;

                contentContainer.innerHTML += blogHtmlModel;
                
                // if the <p> element is taller than 120 pixels add the fade effect
                let parElem = document.getElementById(blog.id);
                let computedStyle = window.getComputedStyle(parElem);
                if (parseFloat(computedStyle.height) >  parseFloat(computedStyle.width) / 5) {
                    parElem.classList.add('p-fade');
                }
            });
            
        },
        error: function (error) {
            console.error('Error:', error);
            isActive = false;
            bottom.innerHTML = "<button class='w-100 text-center alert alert-danger fs-5'>❌ Failed to load Bloggs... Click to try again</button>"
        }
    });
}

function AddComment(comment, toTop = false) {
    let commentHtml = `
        <div class="my-2 border-bottom">
            <a class="link-unstyled py-1 text-decoration-none" href="blogg.php?id=${comment.blog_id}">
                <div class="d-flex">
                    <div class="mx-1 w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <p title="${comment.time}" class="text-body-secondary fs-6 mb-0 mt-1">${timeSince(new Date(comment.time))}</p>
                        </div>
                        <p class="fs-5 mb-2">${comment.text}</p>
                    </div>
                </div>
            </a>
        </div>`;

    contentContainer.innerHTML += commentHtml;
}

function LoadComments() {
    let bottom = document.getElementById("container-bottom");

    contentContainer.innerHTML = '<div class="alert alert-info my-2">Click on a comment to see the original Blogg it was commented on.</div>';
    bottom.innerHTML = "<div class='spinner-border'></div>";

    $.ajax({
        url: 'libraries/comment_manager.php',
        type: 'GET',
        data: {user_id: userId},
        success: function (response) {
            if (response == 'false') {
                return;
            }

            response = JSON.parse(response);

            if (response.length == 0) {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>You haven't left any comments yet.</div>";
            } else {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>That's all of your comments.</div>"
            }

            response.forEach(comment => {
                AddComment(comment);
            });
        },
        error: function (err) {
            alert('Failed to comment 💔')
            console.error(err);
            loadActive = false;
        }
    });
};


// code to run when page loads starts here
let contentContainer = document.getElementById("content-container");
let userId = document.getElementById("user-id").innerText;

LoadBlogs();