let searchParams = new URLSearchParams(window.location.search);
let id = parseInt(searchParams.get('id'));

let container = document.getElementById("blog-container");

let blog;
$.ajax({
    url: "libraries/blog_manager.php",
    method: "GET",
    data: {id: id},
    success: function(response) {

        if (response === 'redirect') {
            window.location.href = "/home.php";
            return;
        }

        blog = JSON.parse(response);
        
        if (blog.content === null) {
            blog.content = '';
        }

        blog.content.replaceAll('\\r\\n','<br>');

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
                <div class="card-body px-5 text-decoration-none"">
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