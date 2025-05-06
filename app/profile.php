<?php
include 'db.php';
include 'session.php';
redirectIfNotLoggedIn();

$errors = [];
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = ? OR phone = ?) AND id != ?");
    }
    $stmt->execute([$email, $phone, $_SESSION['user_id']]);

    if ($stmt->rowCount() > 0) {
        $errors[] = 'Почта или телефон уже заняты.';
    }

    if (empty($errors)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, email = ?, password = ? WHERE id = ?");
            $result = $stmt->execute([$name, $phone, $email, $hashed_password, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, email = ? WHERE id = ?");
            $result = $stmt->execute([$name, $phone, $email, $_SESSION['user_id']]);
        }
        if ($result) {
            $successMessage = 'Информация успешно обновлена.';
        } else {
            $errors[] = 'Ошибка при обновлении информации.';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Профиль</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="../index.php">Главная</a>
    <div class="ml-auto">
        <a href="logout.php" class="btn btn-outline-secondary">Выйти</a>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Профиль пользователя</h2>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo implode('<br>', $errors); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($user['name']); ?>" />
                </div>
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($user['phone']); ?>" />
                </div>
                <div class="form-group">
                    <label for="email">Почта</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>" />
                </div>
                <div class="form-group">
                    <label for="password">Новый пароль <small>(оставьте пустым, чтобы не менять)</small></label>
                    <input type="password" class="form-control" id="password" name="password" />
                </div>
                <button type="submit" class="btn btn-primary btn-block">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
