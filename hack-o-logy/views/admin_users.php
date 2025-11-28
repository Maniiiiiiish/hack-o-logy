<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Users</title>
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
<h4 class="mb-3">Users</h4>
<table class="table table-striped">
<thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Created</th><th></th></tr></thead>
<tbody>
<?php foreach ($users as $u) { ?>
<tr>
<td><?php echo htmlspecialchars($u['name']); ?></td>
<td><?php echo htmlspecialchars($u['email']); ?></td>
<td><?php echo htmlspecialchars($u['role']); ?></td>
<td><?php echo (int)$u['active'] ? 'Yes' : 'No'; ?></td>
<td><?php echo htmlspecialchars($u['created_at']); ?></td>
<td class="text-nowrap">
<a class="btn btn-sm btn-outline-primary" href="?route=admin/users/edit&id=<?php echo (int)$u['id']; ?>">Edit</a>
<form method="post" action="?route=admin/users/toggle" style="display:inline">
<input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
<input type="hidden" name="active" value="<?php echo $u['active']?0:1; ?>">
<button class="btn btn-sm btn-outline-warning"><?php echo $u['active']?'Deactivate':'Activate'; ?></button>
 </form>
<form method="post" action="?route=admin/users/reset" style="display:inline">
<input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
<button class="btn btn-sm btn-outline-danger">Reset Password</button>
 </form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</body>
</html>
