<?php
include 'db.php';
include 'session.php';

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Проверка уникальности
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    }
    $stmt->execute([$email, $phone]);
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Почта или телефон уже заняты.';
    }

    // Проверка совпадения паролей
    if ($password !== $confirm_password) {
        $errors[] = 'Пароли не совпадают.';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $phone, $email, $hashed_password])) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = 'Ошибка при регистрации.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Регистрация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="../index.php">Главная</a>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4">Регистрация</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo implode('<br>', $errors); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="email">Почта</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Повторите пароль</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
            </form>
            <p class="mt-3">Уже зарегистрированы? <a href="../app/login.php">Войти</a></p>
        </div>
    </div>
</div>
</body>
</html>
