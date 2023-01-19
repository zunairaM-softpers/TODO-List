<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import Users</title>
</head>
<body>
    <form action="import" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="report" id="">
        <input type="submit" value="Import">
    </form>
</body>
</html>
