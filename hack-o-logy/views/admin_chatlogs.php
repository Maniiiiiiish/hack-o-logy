<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Chat Logs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="?route=admin/dashboard">Smart Library Admin</a>
<div class="ms-auto"><a class="btn btn-light" href="?route=logout">Logout</a></div>
</div>
</nav>
<div class="container py-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">Chat / AI Logs</h4>
<form method="get" class="d-flex" action="">
<input type="hidden" name="route" value="admin/chatlogs">
<input class="form-control me-2" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" placeholder="Search questions/answers">
<button class="btn btn-primary">Search</button>
</form>
</div>
<div class="row">
<?php foreach ($logs as $l) { $user = htmlspecialchars($l['name'] ?? 'Guest'); $q = htmlspecialchars($l['question']); $a = htmlspecialchars($l['answer'] ?? ''); ?>
<div class="col-md-6 mb-3">
<div class="card gradient-card shadow-sm">
<div class="card-body">
<div class="d-flex justify-content-between align-items-center">
<div class="fw-bold">#<?php echo (int)$l['id']; ?> • <?php echo $user; ?></div>
<div class="text-muted small"><?php echo htmlspecialchars($l['created_at']); ?></div>
</div>
<div class="mt-2"><span class="badge bg-info">Question</span> <?php echo $q; ?></div>
<?php if ($a !== '') { ?><div class="mt-2"><span class="badge bg-success">Answer</span> <?php echo $a; ?></div><?php } ?>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</body>
</html>
