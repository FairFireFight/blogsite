


let contentContainer = document.getElementById("content-container");

contentContainer.innerHTML += blogHTMLModel;

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

function LoadMore() {
    let blogId = "ABC123";
    let blogTitle = "Blogg title";
    let blogContent = "This is the contents of the blog bla bla bla";

    let blogLikes = 100;
    let blogComments = 10;
    let blogTime = "1979/1/1, 12:00am";
    let blogAuthor = "Authorname";
    let blogAuthorId = "123456";
    let blogHearted = false;
    let imageCount = 3;

    // Make the request and get the data for the page
    $.ajax({
        url: 'database/blog_manager.php',
        type: 'GET',
        data: {page: page},
        success: function (response) {
            // Handle the response from the server
            console.log(response);
            // You can update your UI or do other things with the response data
        },
        error: function (error) {
            // Handle errors
            console.error('Error:', error);
        }
    });

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
        <div class="card my-5"> 
            <a class="card-body px-5 text-decoration-none" href="/blogg?id=${blogId}">
                <h3 class="card-title pb-2 fw-semibold border-bottom">${blogTitle}</h3>
                <p class="card-text fs-5">${blogContent}</p>
                ${imageCount > 0 ? imageContainer : ""}
            </a>
            <div class="card-footer px-5">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-start">
                        <div id="${blogId}" class="btn btn-outline-danger fw-semibold me-2 d-inline" onclick="HeartBlog(this)">❤ ${blogLikes}</div>
                        <a class="btn btn-outline-secondary fw-semibold me-2 d-inline" href="/blogg?id=${blogId}">💬 ${blogComments}</a>
                    </div>
                    <p class="align-middle text-end m-0">${blogTime} / <a href="profile.php?id=${blogAuthorId}">${blogAuthor}</a></p>
                </div>
            </div>
        </div>`;
}

