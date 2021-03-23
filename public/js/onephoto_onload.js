window.onload = function () {
    let button = document.getElementById('commentButton');
    let xmlhttp = new XMLHttpRequest();
    let data;

    let newComment = function (event) {
        event.preventDefault();
        let comment = document.getElementById('comment').value;
        let user_id = document.getElementById('user_id').value;
        let photo_id = document.getElementById('photo_id').value;

        if (comment === '') {
            alert('Введите комментарий');
            return false;
        }
        xmlhttp.open('POST', '/newcomment', true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        data = "user_id=" + encodeURIComponent(user_id) +
            "&photo_id=" + encodeURIComponent(photo_id) +
            "&comment=" + encodeURIComponent(comment);
        xmlhttp.send(data);
        document.newcomment.reset();
        getComments();
    }
    button.addEventListener('click', newComment);

    let changeLike = function (event) {
        event.preventDefault();
        let user_id = document.getElementById('user_id').value;
        let photo_id = document.getElementById('photo_id').value;
        xmlhttp.open('POST', '/changelike', true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        data = "user_id=" + encodeURIComponent(user_id) +
            "&photo_id=" + encodeURIComponent(photo_id);
        xmlhttp.send(data);
        getLikes();
    }
    let button2 = document.getElementById('likeButton');
    button2.addEventListener('click', changeLike);
};