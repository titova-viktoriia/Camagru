function getLikes()
{
    let xmlhttp = new XMLHttpRequest();
    let photo_id = document.getElementById('photo_id').value;
    xmlhttp.open('post', '/getlikes', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('photo_id=' + photo_id);
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4) {
            if (xmlhttp.status === 200) {
                let data = xmlhttp.responseText;
                if (data) {
                    data = JSON.parse(data);
                    let element = document.getElementById("likesCount");
                    element.innerText = data.likesCount;
                    element = document.getElementById("like");
                    if (data.usersLike === true) {
                        element.style.color = "red";
                    } else {
                        element.style.color = "black";
                    }
                }
            }
        }
    };
}

window.addEventListener("load", getLikes);