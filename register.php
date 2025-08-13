<?php
// register.php — create a new user securely
session_start();
require 'db.php';

// If already logged in, don’t allow visiting register/login pages
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$name = $email = '';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Collect + trim inputs
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $cpass = $_POST['confirm_password'] ?? '';

    // 2) Validate basics
    if ($name === '' || $email === '' || $pass === '' || $cpass === '') {
        $errors[] = 'Please fill in all fields.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($pass !== $cpass) {
        $errors[] = 'Passwords do not match.';
    }

    // 3) If valid so far, check email is unique and insert
    if (empty($errors)) {
        // Check existing email (prepared statement => SQLi-safe)
        $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = 'This email is already registered. Try logging in.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Server error. Please try again.';
        }
    }

    if (empty($errors)) {
        // Hash password before saving
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                // Optionally set a flash message and redirect to login
                $_SESSION['flash_success'] = 'Registration successful! Please log in.';
                header('Location: login.php');
                exit;
            } else {
                // UNIQUE constraint might fail here too
                $errors[] = 'Registration failed. Email could be in use.';
            }
            $stmt->close();
        } else {
            $errors[] = 'Server error. Please try later.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register | Auth App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="py-5">
<div class="container container-narrow">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="mb-3 text-center">Create an account</h3>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input class="form-control" type="password" name="confirm_password" required>
        </div>
        <button class="btn btn-primary w-100">Register</button>
      </form>

      <p class="mt-3 text-center mb-0">
        Already have an account? <a href="login.php">Log in</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>
