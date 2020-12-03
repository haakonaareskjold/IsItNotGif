<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Is it not a GIF?</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            margin-bottom: 2rem;
        }
        .git {
            display: flex;
            align-self: center;
            margin-top: 1rem;
        }
        .dark {
            background-color: #fff;
            color: #1a202c;
        }
        body {
            background-color: #333;
            color: whitesmoke;
        }
    </style>
</head>
<body>
<div class="container">
    <x-git-plug />
    <x-dark-mode />
    {{ $slot }}
</div>
<script>
    const body = document.querySelector("body");
    const darkCookie = document.cookie.includes("theme=dark");

    if (darkCookie) {
        body.classList.add("dark");
    }

    function toggle() {
        document.cookie = darkCookie ? "theme=" : "theme=dark;max-age=31536000";
        body.classList.toggle("dark");
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
