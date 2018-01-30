<!DOCTYPE html>
<html>
<head>
    <title>Canvas</title>
    <meta charset="utf-8"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <style>
        canvas {
            text-align: center;
        }
    </style>
</head>
<body>
<canvas id='canvas' height="400" width="600"></canvas>
<br/>
<button onclick="next();">Следующая</button>
<button onclick="upload();">Сохранить</button>
<br/>
<div id="files">
    <?php
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/Images/';
    $list = scandir($dir);
    unset($list[0]);
    unset($list[1]);
    foreach ($list as $file) {
        echo '<a target="_blank" href="/lab45/Images/'.$file.'">'.$file.'</a><br />';
    }
    ?>
</div>
<script>
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext("2d");
    var img = new Image();
    img.src = "https://picsum.photos/600/400/?image=" + Math.floor(Math.random() * 1000);
    img.setAttribute('crossOrigin', 'anonymous');
    img.onload = function () {
        context.drawImage(img, 0, 0);
        context.textAlign = "center";
        context.textBaseline = "top";
        context.font = "bold 25px Arial";
        context.fillStyle = "white";
        context.strokeStyle = "black";
        newText();
    };

    function wrapText(context, text) {
        var words = text.split(' ');
        var strings = [];
        var string = "";
        for (var i = 0; i < words.length; i++) {
            if ((string + ' ' + words[i]).length <= 40) {
                string += (' ' + words[i]);
            } else {
                strings.push(string);
                string = words[i];
            }
        }
        strings.push(string);
        var start = (strings.length % 2 === 0) ? (210 - (strings.length / 2) * 25) : (200 - Math.floor(strings.length / 2) * 25) - 12;
        for (var i = 0; i < strings.length; i++) {
            context.fillText(strings[i], 300, start + i * 25);
            context.strokeText(strings[i], 300, start + i * 25);
        }
        context.strokeRect(0, 0, 600, 400);
    }

    function newText() {
        $.ajax({
            url: "https://api.forismatic.com/api/1.0/?method=getQuote&format=jsonp&lang=ru&jsonp=?",
            dataType: "jsonp",
            success: function (data) {
                text = data.quoteText;
                wrapText(context, text);
            }
        });
    }

    function next() {
        context.clearRect(0, 0, 600, 400);
        img = new Image();
        img.src = "https://picsum.photos/600/400/?image=" + Math.floor(Math.random() * 1000);
        img.setAttribute('crossOrigin', 'anonymous');
        img.onload = function () {
            context.drawImage(img, 0, 0);
            newText();
        }
    }

    function upload() {
        var img = document.getElementById("canvas");
        var imgData = img.toDataURL();
        $.ajax({
            type: 'POST',
            url: 'upload.php',
            data: {
                data: imgData
            },
            success: function(response) {
                document.body.removeChild(document.getElementById("files"));
                var div = document.createElement("div");
                div.setAttribute("id", "files")
                div.innerHTML = response;
                document.body.appendChild(div);
            }
        });
    }
</script>
</body>
</html>