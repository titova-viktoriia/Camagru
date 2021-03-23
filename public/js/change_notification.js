window.onload = function () {
    let button = document.getElementById('notificationButton');
    let xmlhttp = new XMLHttpRequest();

    let changeNotification = function (event) {
        event.preventDefault();
        xmlhttp.open('POST', '/changenotification', true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.send();
        showNotification();
    }

    button.addEventListener('click', changeNotification);
}