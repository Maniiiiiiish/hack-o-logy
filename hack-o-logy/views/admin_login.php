<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-4">
<div class="card shadow">
<div class="card-body">
<h3 class="mb-3">Admin Login</h3>
<?php if (isset($error)) { ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>
<form method="post" action="?route=admin/login">
<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>
<div class="d-grid gap-2">
<button class="btn btn-primary" type="submit">Login</button>
<a class="btn btn-outline-secondary" href="?route=login">User Login</a>
</div>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
