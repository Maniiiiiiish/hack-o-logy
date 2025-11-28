<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand" href="?route=user/dashboard">Smart Library</a>
<div class="ms-auto"><a class="btn btn-light" href="?route=logout">Logout</a></div>
</div>
</nav>
<div class="container py-4">
<h3 class="mb-3">My Books</h3>
<table class="table table-striped">
<thead><tr><th>Title</th><th>Author</th><th>Issue</th><th>Due</th><th>Status</th><th></th></tr></thead>
<tbody>
<?php foreach ($items as $it) { ?>
<tr>
<td><?php echo htmlspecialchars($it['title']); ?></td>
<td><?php echo htmlspecialchars($it['author']); ?></td>
<td><?php echo htmlspecialchars($it['issue_date']); ?></td>
<td><?php echo htmlspecialchars($it['due_date']); ?></td>
<td><?php echo htmlspecialchars($it['status']); ?></td>
<td>
<?php if ($it['status']==='issued') { ?>
<form method="post" action="?route=return">
<input type="hidden" name="issue_id" value="<?php echo (int)$it['id']; ?>">
<button class="btn btn-sm btn-outline-danger">Return</button>
</form>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</body>
</html>
