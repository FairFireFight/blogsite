let searchParams = new URLSearchParams(window.location.search);
let blogId = parseInt(searchParams.get('id'));

let container = document.getElementById("blog-container");

let blog;
$.ajax({
    url: "libraries/blog_manager.php",
    method: "GET",
    data: {id: blogId},
    success: function(response) {

        if (response === 'redirect') {
            window.location.href = "/home.php";
            return;
        }

        blog = JSON.parse(response);
        
        if (blog.content === null) {
            blog.content = '';
        }

        // load content
        let imageCount = blog.image_count;

        let imageContainer = `
            <div class="container">
                <div class="row">
                    ${imageCount >= 1 ? '<img class="blog-image col p-0" src="' + blog.id + '/1"/>' : ""}
                    ${imageCount >= 2 ? '<img class="blog-image col p-0" src="' + blog.id + '/2"/>' : ""}
                </div>
                <div class="row"> 
                    ${imageCount >= 3 ? '<img class="blog-image col p-0" src="' + blog.id + '/3"/>' : ""}
                    ${imageCount >= 4 ? '<img class="blog-image col p-0" src="' + blog.id + '/4"/>' : ""}
                </div>
            </div>`;

        let blogHTMLModel = `
            <div class="card my-3 w-100"> 
                <div class="card-body px-4 text-decoration-none"">
                    <h3 class="card-title pb-2 fw-semibold border-bottom">${blog.title}</h3>
                    <p class="card-text fs-5">${blog.content}</p>
                    ${imageCount > 0 ? imageContainer : ""}
                </div>
                <div class="card-footer px-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-start">
                            <div id="${blog.id}" class="btn ${blog.liked ? "btn-danger" : "btn-outline-danger"} fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blog.likes}</div>
                        </div>
                    </div>
                </div>
            </div>`;

        container.innerHTML = blogHTMLModel;

        // comment header
        let commentHeader = document.getElementById('comment-header');
        commentHeader.innerHTML = `${blog.comments} Comment${blog.comments === 1 ? '' : 's'}`;

        // handle time display
        let timeElem = document.getElementById('time-since');
        timeElem.innerHTML = timeSince(new Date(blog.blog_time));
        timeElem.title = blog.blog_time;

        // handle authorname
        let authorElem = document.getElementById('authorname');
        authorElem.innerHTML = blog.author;
        authorElem.href = "profile?id=" + blog.author_id;
    },
    error: function(err) {
        console.log(err);
    }
});


let commentActive = false;
function SendComment() {
    let text = document.getElementById("comment-input").value;

    if (text.length == 0) {
        alert("Comment can not be empty.");
        return;
    }

    commentActive = true;
    $.ajax({
        url: 'libraries/comment_manager.php',
        type: 'GET',
        data: {comment: text,
               blog_id: blogId},
        success: function (response) {
            if (response == 'false') {
                return;
            }

            response = JSON.parse(response);

            AddComment(response, true);

            commentActive = false;
        },
        error: function (err) {
            alert('Failed to comment 💔')
            console.error(err);
            commentActive = false;
        }
    });
}

function AddComment(comment, toTop = false) {
    let commentHtml = `
        <div class="card mt-2 mb-3">
            <div class="card-body py-1">
                <div class="d-flex">
                    <div class="ms-0 me-2">
                        <img class="rounded-circle shadow mt-2" style="max-width: 30vw; width: 50px;" src="/uploads/images/profile_pictures/default.jpg">
                    </div>
                    <div class="mx-2 w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fs-4 mb-0 mt-1">${comment.username}</p>
                            <p title="${comment.time}" class="text-body-secondary fs-6 mb-0 mt-1">${timeSince(new Date(comment.time))}</p>
                        </div>
                        <hr class="my-2">
                        <p class="fs-5 mb-2">${comment.text}</p>
                    </div>
                </div>
            </div>
        </div>`;

        
    let commentContainer = document.getElementById('comment-container');
    if (toTop) {
        commentContainer.innerHTML = commentHtml + commentContainer.innerHTML;
    } else {
        commentContainer.innerHTML += commentHtml;
    }
}
    

let loadActive = false;
function LoadComments() {
    loadActive = true;
    $.ajax({
        url: 'libraries/comment_manager.php',
        type: 'GET',
        data: {blog_id: blogId},
        success: function (response) {
            if (response == 'false') {
                return;
            }

            response = JSON.parse(response);

            response.forEach(comment => {
                AddComment(comment);
            });

            loadActive = false;
        },
        error: function (err) {
            alert('Failed to comment 💔')
            console.error(err);
            loadActive = false;
        }
    });
}

LoadComments();