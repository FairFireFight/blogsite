function HeartBlog(e) {
    // handling the color of the button 
    if (e.classList.contains('btn-outline-danger')) {
        e.classList.remove('btn-outline-danger');
        e.classList.add('btn-danger');
    } else {
        e.classList.add('btn-outline-danger');
        e.classList.remove('btn-danger');
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
        data: {page: page},
        success: function (response) {
            let jsonArray = JSON.parse(response);
            
            if (jsonArray.length == 0) {
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>😐 We can't find more Bloggs</div>";
            } else {
                bottom.innerHTML = "<div class='spinner-border'></div>"
            }

            page++;
            
            jsonArray.forEach(blog => {
                let blogId = blog.id;
                let blogAuthorId = blog.author_id;
                let blogTitle = blog.title;
                let blogAuthor;
                let blogContent = blog.content;

                let blogLikes = 0;
                let blogComments = 0;
                let blogTime = blog.time;
                let blogHearted = blog.liked;
                let imageCount = 0;

                // yes this is probably THE worst way you could do this.
                let imageContainer = `
                    <div class="container">
                        <div class="row">
                            ${imageCount >= 1 ? '<img class="blog-image col p-0" src="' + blogId + '/1"/>' : ""}
                            ${imageCount >= 2 ? '<img class="blog-image col p-0" src="' + blogId + '/2"/>' : ""}
                        </div>
                        <div class="row"> 
                            ${imageCount >= 3 ? '<img class="blog-image col p-0" src="' + blogId + '/3"/>' : ""}
                            ${imageCount >= 4 ? '<img class="blog-image col p-0" src="' + blogId + '/4"/>' : ""}
                        </div>
                    </div>`;

                let blogHTMLModel = `
                    <div class="card my-3"> 
                        <a class="card-body px-5 text-decoration-none" href="/blogg?id=${blogId}">
                            <h3 class="card-title pb-2 fw-semibold border-bottom">${blogTitle}</h3>
                            <p class="card-text fs-5">${blogContent}</p>
                            ${imageCount > 0 ? imageContainer : ""}
                        </a>
                        <div class="card-footer px-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-start">
                                    <div id="${blogId}" class="btn ${blogHearted ? "btn-danger" : "btn-outline-danger"} fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blogLikes}</div>
                                    <a class="btn btn-outline-secondary fw-semibold me-2 d-inline" href="/blogg?id=${blogId}">💬 ${blogComments}</a>
                                </div>
                                <p class="align-middle text-end m-0">${blogTime} / <a href="profile.php?id=${blogAuthorId}">${blogAuthor}</a></p>
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

// code to run when page starts here
let contentContainer = document.getElementById("content-container");
LoadMore();