<?php
// dashboard.php — protected page; only logged-in users can see it
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$name = $_SESSION['name'] ?? 'User';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard | Auth App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="py-5">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h3 class="mb-3">Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
          <p class="text-muted">You are logged in. This page is protected by sessions.</p>
          <a class="btn btn-outline-danger" href="logout.php">Log out</a>
        </div>
      </div>
      <p class="text-center mt-3 text-muted small">
        (Tip: Try visiting <code>login.php</code> or <code>register.php</code> now — you’ll be redirected.)
      </p>
    </div>
  </div>
</div>
</body>
</html>
