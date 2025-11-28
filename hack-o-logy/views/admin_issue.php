<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Issue Manager</title>
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
<h4 class="mb-3">Issue Book</h4>
<?php if ($message) { ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php } ?>
<form method="post" action="?route=admin/issue" class="row g-2">
<div class="col-md-4">
<select class="form-select" name="user_id">
<?php foreach ($users as $u) { ?><option value="<?php echo (int)$u['id']; ?>"><?php echo htmlspecialchars($u['name'].' ('.$u['email'].')'); ?></option><?php } ?>
 </select>
</div>
<div class="col-md-4">
<select class="form-select" name="book_id">
<?php foreach ($books as $b) { ?><option value="<?php echo (int)$b['id']; ?>"><?php echo htmlspecialchars($b['title'].' ('.$b['available_copies'].' avail)'); ?></option><?php } ?>
 </select>
</div>
<div class="col-md-4"><button class="btn btn-primary w-100">Issue</button></div>
</form>
</div>
</body>
</html>
