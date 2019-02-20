<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel + AWS Rekognition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
</head>
<body>

<div class="container">

    <form action="{{ action('PhotosController@submitFormUpload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="photo">Upload a Photo</label>
            <input type="file" name="photo" id="photo" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Submit" class="btn btn-success btn-lg">
        </div>
    </form>

</div>

</body>
</html>