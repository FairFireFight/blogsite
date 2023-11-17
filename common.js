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
