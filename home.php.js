let heartActive = false;
function HeartBlog(e) {
    heartActive = true;
    $.ajax({
        url: 'libraries/blog_manager.php',
        type: 'POST',
        data: {heart_blog: e.id},
        success: function (response) {
            if (response == 'redirect') {
                alert('You must sign up to heart Bloggs');
                return;
            }

            if (response == 'error') {
                alert('Failed to Heart Blogg 💔')
                return;
            }

            heartActive = false;
            HandleHeartBlogButton(e)
        },
        error: function (err) {
            alert('Failed to Heart Blogg 💔')
            console.error(err);
            heartActive = false;
        }
    });
}

function HandleHeartBlogButton(e) {
    if (e.classList.contains('btn-outline-danger')) {
        e.classList.remove('btn-outline-danger');
        e.classList.add('btn-danger');

        e.innerHTML = "❤ " + (parseInt(e.innerHTML.substring(2)) + 1);
    } else {
        e.classList.add('btn-outline-danger');
        e.classList.remove('btn-danger');

        e.innerHTML = "❤ " + (parseInt(e.innerHTML.substring(2)) - 1);
    }
}

let page = 1;
let isActive = false
function LoadMore() {
    let bottom = document.getElementById("container-bottom");

    isActive = true;
    // Make the request and get the data for the page
    $.ajax({
        url: 'libraries/blog_manager.php',
        type: 'GET',
        data: {page: page,
               search: search},
        success: function (response) {
            let jsonArray = JSON.parse(response);
            
            if (jsonArray.length <= 3) {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>😐 We can't find more Bloggs</div>";
            } else {
                bottom.innerHTML = "<div class='spinner-border'></div>"
            }

            if (jsonArray.length == 0) {
                return;
            }

            page++;
            
            jsonArray.forEach(blog => {
                let blogComments = 0;
                let imageCount = 0;

                // yes this is probably THE worst way you could do this.
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
                    <div class="card my-3"> 
                        <a class="card-body px-5 text-decoration-none" href="/blogg?id=${blog.id}">
                            <h3 class="card-title pb-2 fw-semibold border-bottom">${blog.title}</h3>
                            <p class="card-text fs-5">${blog.content}</p>
                            ${imageCount > 0 ? imageContainer : ""}
                        </a>
                        <div class="card-footer px-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-start">
                                    <div id="${blog.id}" class="btn ${blog.liked ? "btn-danger" : "btn-outline-danger"} fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blog.likes}</div>
                                    <a class="btn btn-outline-secondary fw-semibold me-2 d-inline" href="/blogg?id=${blog.id}">💬 ${blogComments}</a>
                                </div>
                                <p class="align-middle text-end m-0">${blog.time} / <a href="profile.php?id=${blog.author_id}">${blog.author}</a></p>
                            </div>
                        </div>
                    </div>`;

                contentContainer.innerHTML += blogHTMLModel;
                isActive = false;
            });
            
        },
        error: function (error) {
            console.error('Error:', error);
            isActive = false;
            bottom.innerHTML = "<button class='w-100 text-center alert alert-danger fs-5'>⛔ Failed to load more bloggs... Click to try again</button>"
        }
    });
}

window.addEventListener('scroll', function () {
    var distanceToBottom = document.documentElement.scrollHeight - (window.innerHeight + window.scrollY);
    var threshold = 75;

    if (distanceToBottom < threshold && !isActive) {
        LoadMore();
    }
});

// code to run when page loads starts here
let contentContainer = document.getElementById("content-container");
let search = document.getElementById("search-bar").value;
LoadMore();