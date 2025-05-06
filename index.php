<?php include 'app/session.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Главная страница</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="#">Главная</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <?php if (!isLoggedIn()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="app/register.php">Регистрация</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="app/login.php">Авторизация</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="app/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="app/logout.php">Выход</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="jumbotron text-center">
        <h1 class="display-4">Добро пожаловать!</h1>
        <?php if (isLoggedIn()): ?>
            <p class="lead">Вы авторизованы. Перейдите в ваш <a href="app/profile.php">профиль</a>.</p>
        <?php else: ?>
            <p class="lead">Пожалуйста, <a href="app/login.php">войдите</a> или <a href="app/register.php">зарегистрируйтесь</a>.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
