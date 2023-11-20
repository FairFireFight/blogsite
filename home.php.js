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
                bottom.innerHTML = "<div class='w-100 text-center alert alert-warning fs-5'>That's all the Bloggs 🙁</div>";
            } else {
                bottom.innerHTML = "<div class='spinner-border'></div>"
            }

            if (jsonArray.length == 0) {
                return;
            }

            page++;
            
            jsonArray.forEach(blog => {
                let imageCount = blog.image_count;

                if (blog.content === null) {
                    blog.content = '';
                } else {
                    blog.content = blog.content.replaceAll('\\r\\n','<br>');
                }


                // yes this is probably THE worst way you could do this.
                let imageContainer = `
                    <div class="container mx-auto w-75">
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
                        <a class="card-body px-5 text-decoration-none" href="/blogg.php?id=${blog.id}">
                            <h3 class="card-title fw-semibold ${blog.content == '' ? '' : 'border-bottom pb-2'}">${blog.title}</h3>
                            <p id='${blog.id}'class="card-text fs-5">${blog.content.substr(0, 512)}</p>
                            ${imageCount > 0 ? imageContainer : ""}
                        </a>
                        <div class="card-footer px-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center justify-content-start">
                                    <div id="${blog.id}" class="btn ${blog.liked ? "btn-danger" : "btn-outline-danger"} fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blog.likes}</div>
                                    <a class="btn btn-outline-secondary fw-semibold me-2 d-inline" href="/blogg.php?id=${blog.id}">💬 ${blog.comments}</a>
                                </div>
                                <p class="align-middle text-end m-0">${blog.blog_time} / <a href="profile.php?id=${blog.author_id}">${blog.author}</a></p>
                            </div>
                        </div>
                    </div>`;

                contentContainer.innerHTML += blogHTMLModel;
                
                // if the <p> element is taller than 120 pixels add the fade effect
                let parElem = document.getElementById(blog.id);
                let computedStyle = window.getComputedStyle(parElem);
                if (parseFloat(computedStyle.height) >  parseFloat(computedStyle.width) / 5) {
                    parElem.classList.add('p-fade');
                }

                isActive = false;
            });
            
        },
        error: function (error) {
            console.error('Error:', error);
            isActive = false;
            bottom.innerHTML = "<button class='w-100 text-center alert alert-danger fs-5'>❌ Failed to load more bloggs... Click to try again</button>"
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