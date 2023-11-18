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

function timeSince(date) {
    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = seconds / 31536000;

    if (interval > 1) {
        return Math.floor(interval) + ` year${Math.floor(interval) == 1 ? '' : 's' }`;
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        return Math.floor(interval) + ` month${Math.floor(interval) == 1 ? '' : 's' }`;
    }
    interval = seconds / 86400;
    if (interval > 1) {
        return Math.floor(interval) + ` day${Math.floor(interval) == 1 ? '' : 's' }`;
    }
    interval = seconds / 3600;
    if (interval > 1) {
        return Math.floor(interval) + ` hour${Math.floor(interval) == 1 ? '' : 's' }`;
    }
    interval = seconds / 60;
    if (interval > 1) {
        return Math.floor(interval) + ` minute${Math.floor(interval) == 1 ? '' : 's' }`;
    }

    return Math.floor(seconds) + ` second${Math.floor(interval) == 1 ? '' : 's' }`;
}