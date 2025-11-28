<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit User</title>
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
<h4 class="mb-3">Edit User</h4>
<form method="post" action="?route=admin/users/edit&id=<?php echo (int)$user['id']; ?>">
<div class="mb-2"><input class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Name"></div>
<div class="mb-2"><input class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email"></div>
<div class="mb-2">
<select class="form-select" name="role">
<option value="user" <?php echo $user['role']==='user'?'selected':''; ?>>User</option>
<option value="admin" <?php echo $user['role']==='admin'?'selected':''; ?>>Admin</option>
</select>
</div>
<button class="btn btn-success">Save</button>
<a class="btn btn-secondary" href="?route=admin/users">Cancel</a>
</form>
</div>
</body>
</html>
