<?php
include 'db.php';
include 'session.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Проверка существования пользователя
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = 'Неверный логин или пароль.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="../index.php">Главная</a>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Авторизация</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo implode('<br>', $errors); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="login">Email или телефон:</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div id="captcha-container" class="mb-3"></div>
                <button type="submit" class="btn btn-primary btn-block" id="smartcaptcha-demo-submit" disabled>Войти</button>
            </form>
            <p class="mt-3">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </div>
    </div>
</div>

<script src="https://smartcaptcha.yandexcloud.net/captcha.js?render=onload&onload=smartCaptchaInit" defer></script>
<script>
    function callback(token) {
        console.log(token);
        if (token) {
            document.getElementById('smartcaptcha-demo-submit').removeAttribute('disabled');
        } else {
            document.getElementById('smartcaptcha-demo-submit').setAttribute('disabled', '1');
        }
    }

    function smartCaptchaInit() {
        if (!window.smartCaptcha) {
            return;
        }

        window.smartCaptcha.render('captcha-container', {
            sitekey: 'ysc1_T660pNtOOhG85PjjVqRN9VKK5Sl9VOop9EIKNebof8d6bd6c',
            callback: callback,
        });
    }

    function smartCaptchaReset() {
        if (!window.smartCaptcha) {
            return;
        }

        window.smartCaptcha.reset();
    }

    function smartCaptchaGetResponse() {
        if (!window.smartCaptcha) {
            return;
        }

        var resp = window.smartCaptcha.getResponse();
        console.log(resp);
        alert(resp);
    }
</script>
</body>
</html>