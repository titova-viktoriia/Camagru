function getComments()
{
    let xmlhttp = new XMLHttpRequest();
    let photo_id = document.getElementById('photo_id').value;
    let activeUserId = get_cookie("user_id")
    xmlhttp.open('post', '/getcomments', true);
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlhttp.send('photo_id=' + photo_id);
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4) {
            if (xmlhttp.status === 200) {
                let data = xmlhttp.responseText;
                if (data) {
                    data = JSON.parse(data);
                    let i = 0;
                    let childrensLength = document.getElementById('here2').children.length;
                    let dataLength = data.length;
                    if (childrensLength > 0) {
                        i = dataLength - 1;
                    }

                    while ( i < dataLength) {
                        let parent = document.getElementsByClassName('here')[0];
                        let elem = document.createElement('div');
                        elem.className = 'comments';
                        elem.id = "comment_id_" + data[i].id;

                        parent = parent.appendChild(elem);
                        elem = document.createElement('span');
                        parent.appendChild(elem);
                        let text = data[i].created_at + " ";
                        let textNode = document.createTextNode(text);
                        elem.appendChild(textNode);
                        elem = document.createElement('span');
                        parent.appendChild(elem);
                        text = data[i].login;
                        textNode = document.createTextNode(text);
                        elem.appendChild(textNode);
                        elem = document.createElement('div');
                        elem.className = 'comment';
                        parent.appendChild(elem);
                        text = data[i].comment_text;
                        textNode = document.createTextNode(text);
                        elem.appendChild(textNode);

                        if (data[i].user_id === activeUserId) {
                            elem = document.createElement('button');
                            elem.className = "btn btn-outline-secondary";
                            elem.id = "deletecomment";
                            elem.value = data[i].id;
                            elem.onclick = function() {
                                deleteComment(this);
                            };
                            elem.innerHTML = "Удалить комментарий";
                            parent.appendChild(elem);
                        }

                        elem = document.createElement('hr');
                        parent.appendChild(elem);
                        i++;
                    }
                }
            }
        }
    };

    function get_cookie(cookie_name)
    {
        let results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

        if (results) {
            return (unescape(results[2]));
        } else {
            return null;
        }
    }
}

window.addEventListener("load", getComments);