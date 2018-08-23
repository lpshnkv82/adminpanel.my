<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?=PATH.ADMIN_TEMPLATE?>authstyle/style.css">
    <title>Страница авторизации</title>
</head>
<body>
<div class="content login_page">

    <div>
        <?php if($error):?>
            <p style="color:red"><?=$error?></p>
        <?php endif;?>
        <h1>Авторизация</h1>

        <form method="post" action="<?=PATH?>login">
            <label for="login">Имя пользователя</label>
            <input type="text" name="login">
            <label for="password">Пароль</label>
            <input type="password" name="password">
            <input type="submit" name="submit" value="Войти">
        </form>
    </div>
</div>
</body>
</html>