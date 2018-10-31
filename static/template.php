<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script type="text/css" src="/static/style.css" >

    </script>
</head>
<body>
<div class="menu">

    <a href="./">Home</a>
    <?php if ($authed) {?>
        <a href="?module=listTasks">List tasks</a>
        <a href="?module=auth&logout=1">Logout</a>
    <?php } else {?>
    <a href="?module=auth">Login</a>
    <a href="?module=editTask">Submit new</a>
    <?php } ?>
</div>
<?php echo $contents; ?>
</body>
</html>