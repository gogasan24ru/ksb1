<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" type="text/css" href="static/style.css">
</head>
<body>
<div class="menu">

    <a href="./">Home</a>
    <a href="?module=editTask">Submit new</a>
    <?php if ($authed) {?>
        <a href="?module=listTasks">List tasks</a>
        <a href="?module=auth&logout=1">Logout (logged in as <?php echo $authed ;?>)</a>
    <?php } else {?>
    <a href="?module=auth">Login</a>
    <?php } ?>
</div>
<div class="contents">
    <?php echo $contents; ?>
</div>
</body>
</html>