<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="?route=admin/dashboard">Smart Library Admin</a>
<div class="ms-auto">
<a class="btn btn-light" href="?route=logout">Logout</a>
</div>
</div>
</nav>
<div class="container py-4">
<h3 class="mb-3">Hello <?php echo $name; ?></h3>
<div class="row g-3">
<div class="col-md-3">
<div class="card text-center"><div class="card-body"><div>Total Books</div><div class="fs-3"><?php echo (int)$totalBooks; ?></div></div></div>
 </div>
<div class="col-md-3">
<div class="card text-center"><div class="card-body"><div>Total Users</div><div class="fs-3"><?php echo (int)$totalUsers; ?></div></div></div>
 </div>
<div class="col-md-3">
<div class="card text-center"><div class="card-body"><div>Issued Books</div><div class="fs-3"><?php echo (int)$issuedCount; ?></div></div></div>
 </div>
<div class="col-md-3">
<div class="card text-center"><div class="card-body"><div>Returned Books</div><div class="fs-3"><?php echo (int)$returnedCount; ?></div></div></div>
 </div>
</div>
<div class="row mt-4">
<div class="col-md-6">
<div class="card"><div class="card-body">
<h5>Popular Categories</h5>
<ul>
<?php foreach ($popularCategories as $pc) { ?><li><?php echo htmlspecialchars($pc['category']); ?> (<?php echo (int)$pc['cnt']; ?>)</li><?php } ?>
 </ul>
</div></div>
</div>
<div class="col-md-6">
<div class="card"><div class="card-body">
<h5>Most Issued Books</h5>
<ul>
<?php foreach ($mostIssued as $mi) { ?><li><?php echo htmlspecialchars($mi['title']); ?> (<?php echo (int)$mi['cnt']; ?>)</li><?php } ?>
 </ul>
</div></div>
</div>
</div>
<div class="row mt-4">
<div class="col-md-6">
<div class="card"><div class="card-body">
<h5>Daily Issues</h5>
<canvas id="issuesChart"></canvas>
</div></div>
</div>
<div class="col-md-6">
<div class="card"><div class="card-body">
<h5>Recent Activity</h5>
<ul>
<?php foreach ($recent as $r) { ?><li>#<?php echo (int)$r['id']; ?> <?php echo htmlspecialchars($r['name']); ?> - <?php echo htmlspecialchars($r['title']); ?> (<?php echo htmlspecialchars($r['status']); ?>)</li><?php } ?>
 </ul>
</div></div>
</div>
</div>
<div class="mt-4 d-flex gap-2">
<a href="?route=admin/books" class="btn btn-outline-secondary">Manage Books</a>
<a href="?route=admin/users" class="btn btn-outline-secondary">Manage Users</a>
<a href="?route=admin/issue" class="btn btn-outline-secondary">Issue Manager</a>
<a href="?route=admin/return" class="btn btn-outline-secondary">Return Manager</a>
<a href="?route=admin/chatlogs" class="btn btn-outline-secondary">Chat Logs</a>
<a href="?route=admin/settings" class="btn btn-outline-secondary">Settings</a>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?php echo json_encode(array_map(function($x){return $x['d'];}, $issuesPerDay)); ?>;
const data = <?php echo json_encode(array_map(function($x){return (int)$x['c'];}, $issuesPerDay)); ?>;
new Chart(document.getElementById('issuesChart'), { type: 'line', data: { labels, datasets: [{ label: 'Issues', data, borderColor: '#4e73df'}] } });
</script>
<div class="container py-2">
<div class="row mt-2">
<div class="col-md-12">
<div class="card"><div class="card-body">
<h5>Monthly Registrations</h5>
<canvas id="regChart"></canvas>
</div></div>
</div>
</div>
</div>
<script>
const regLabels = <?php echo json_encode(array_map(function($x){return $x['m'];}, $registrations)); ?>;
const regData = <?php echo json_encode(array_map(function($x){return (int)$x['c'];}, $registrations)); ?>;
new Chart(document.getElementById('regChart'), { type: 'bar', data: { labels: regLabels, datasets: [{ label: 'Registrations', data: regData, backgroundColor: '#1cc88a'}] } });
</script>
</body>
<script src="assets/assistant.js"></script>
</html>
