let mode = 1;
let base64dataUrl ;
let base64dataUrl_2;
let dX = 80;
let dY = 80;
let size = 200;
let zIndex = 0;
let current;
let width = getWidth();
let height = getHeight();
const getCanvasVideo = () => document.getElementById("canvasVideo");

window.onload = function () {

    let button = document.getElementById('button');
    let saveButton = document.getElementById('saveButton');
    let increaseButton = document.getElementById('increaseButton');
    let decreaseButton = document.getElementById('decreaseButton');
    let cleanButton = document.getElementById('cleanButton');

    let canvas = document.getElementById('canvas');
    let canvasMasks = document.getElementById('canvasMasks');
    let video = document.getElementById('video');
    let image = document.getElementById('image');
    let help = document.getElementById('help');
    let help2 = document.getElementById('help2');

    video.width = width;
    video.height = height;
    canvas.width = width;
    canvas.height = height;
    image.width = width;
    image.height = height;
    help.width = width;
    help.height = height;
    help2.width = width;
    help2.height = height;
    canvasMasks.width = width;
    canvasMasks.height = height;

    let context = canvas.getContext('2d');
    let contextMasks = canvasMasks.getContext('2d');

    let captureMe = function () {
        if (mode === 1) {
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, video.width, video.height);
            context.setTransform(1, 0, 0, 1, 0, 0);
        } else {
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.drawImage(image, 0, 0, image.width, image.height);
        }

        const canvasVideo = getCanvasVideo();
        contextMasks.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < canvasVideo.children.length; i++) {
            const currentCanvas = canvasVideo.children[i];
            currentCanvas.getContext('2d');
            contextMasks.drawImage(currentCanvas, 0, 0);
        }

        base64dataUrl = canvas.toDataURL('image/png');
        base64dataUrl_2 = canvasMasks.toDataURL('image/png');
    }

    let saveMe = function () {
        let xhr = new XMLHttpRequest();
        let comment = document.getElementById('comment').value;
        let data = "description=" + comment +
            "&photo=" + encodeURIComponent(base64dataUrl) +
            "&masks=" + encodeURIComponent(base64dataUrl_2);
        xhr.open('POST', '/save', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(data);
    }

    button.addEventListener('click', captureMe);
    saveButton.addEventListener('click', saveMe);
    increaseButton.addEventListener('click', increase);
    decreaseButton.addEventListener('click', decrease);
    cleanButton.addEventListener('click', clean);

    navigator.mediaDevices.getUserMedia({video: true, audio: false})
        .then(stream => {
            video.srcObject = stream
            video.play();
        });
};

function addMask(mask) {
    current = mask;
    const imgId = "mask_canvas_" + mask;
    let element = document.getElementById(imgId);

    if (element) {
        element.parentNode.removeChild(element);
        current = "";
    } else {
        showMasks();
    }
}

function showMasks() {
    if (current) {
        const imgId = "mask_canvas_" + current;

        let element = document.getElementById(imgId);
        if (element)
            element.parentNode.removeChild(element);

        let maskElement = document.getElementById(current);
        let canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        canvas.draggable = true;
        canvas.id = imgId;
        canvas.style.zIndex = zIndex;
        canvas.style.position = 'absolute'
        canvas.addEventListener("click", getClickPosition, false);;

        getCanvasVideo().appendChild(canvas);

        let img = new Image();
        img.src = maskElement.value;

        let context = canvas.getContext('2d');
        context.drawImage(img, dX, dY, size, size);
        zIndex++;
    }
}

function getClickPosition(e) {
    if (current) {
        let rect = getCanvasVideo().getBoundingClientRect();
        dX = e.clientX - rect.left - (size / 2);
        dY = e.clientY - rect.top - (size / 2);
        showMasks(current);
    }
}

function increase() {
    if (current) {
        size += 20;
        showMasks(current);
    }
}

function decrease() {
    if (current) {
        size -= 20;
        if (size < 20)
            size = 20;
        showMasks(current);
    }
}

function uploadPic(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        let image = document.getElementById('image');
        reader.onload = function(e) {
            let file_type = input.files[0].type;
            if (file_type !== 'image/jpeg' && file_type !== 'image/png') {
                alert('только jpg и png');
                return ;
            }

            image.style.display = "";
            image.setAttribute('src', e.target.result);
            image.height = height;
            image.width = width;
            document.getElementById('video').style.display = "none";
        };
        reader.readAsDataURL(input.files[0]);
        mode = 2;
    }
}

function clean () {
    let image = document.getElementById('image');
    image.style.display = "none";
    image.removeAttribute('src');

    let canvas = document.getElementById('canvas');
    let context = canvas.getContext('2d');
    context.clearRect(0, 0, width, height);

    let canvasMasks = document.getElementById('canvasMasks');
    let contextMasks = canvasMasks.getContext('2d');
    contextMasks.clearRect(0, 0, width, height);

    document.getElementById('comment').value = "";

    for (let i = 0; i <= 21; i++) {
        var name = "mask_id_" + i;
        document.getElementById(name).checked = false;
    }

    base64dataUrl = "";
    base64dataUrl_2 = "";

    getCanvasVideo().innerHTML = "";

    document.getElementById('video').style.display = "";
    mode = 1;
}

function getWidth() {
    if (window.screen.width > 1100) {
        return (448);
    } else {
        return (window.screen.width * 0.70);
    }
}

function getHeight() {
    if (window.screen.width > 1100) {
        return (336);
    } else {
        return (336 * getWidth() / 448);
    }
}