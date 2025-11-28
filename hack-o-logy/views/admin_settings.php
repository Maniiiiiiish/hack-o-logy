<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Settings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="?route=admin/dashboard">Smart Library Admin</a>
<div class="ms-auto"><a class="btn btn-light" href="?route=logout">Logout</a></div>
</div>
</nav>
<div class="container py-4">
<h4 class="mb-3">Settings</h4>
<?php if (!empty($msg)) { ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php } ?>
<form method="post" action="?route=admin/settings" class="col-md-6">
<div class="mb-2"><label class="form-label">Name</label><input class="form-control" name="name" value="<?php echo htmlspecialchars($me['name']); ?>"></div>
<div class="mb-2"><label class="form-label">Email</label><input class="form-control" name="email" value="<?php echo htmlspecialchars($me['email']); ?>"></div>
<div class="mb-2"><label class="form-label">New Password</label><input class="form-control" type="password" name="password" placeholder="Leave blank to keep"></div>
<button class="btn btn-primary">Save</button>
</form>
</div>
</body>
</html>
