<?php
// login.php — verify credentials and start a session
session_start();
require 'db.php';

// If already logged in, skip login
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$email = '';
$errors = [];

// Optional flash message from register
$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $errors[] = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    } else {
        // Fetch user by email
        $sql = "SELECT id, name, password FROM users WHERE email = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->bind_result($id, $name, $hash);
            if ($stmt->fetch()) {
                // Check password
                if (password_verify($pass, $hash)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['name'] = $name;
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $errors[] = 'Invalid email or password.';
                }
            } else {
                $errors[] = 'Invalid email or password.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Server error. Please try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login | Auth App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="py-5">
<div class="container container-narrow">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="mb-3 text-center">Welcome back</h3>

      <?php if ($flash): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($flash); ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <button class="btn btn-primary w-100">Log in</button>
      </form>

      <p class="mt-3 text-center mb-0">
        New here? <a href="register.php">Create an account</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>
