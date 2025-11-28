<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Search Books</title>
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
<h3 class="mb-3">Search</h3>
<form class="row g-2" method="get" action="">
<input type="hidden" name="route" value="search">
<div class="col-md-5"><input class="form-control" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Title, author, category, ISBN"></div>
<div class="col-md-3">
<select class="form-select" name="category">
<option value="">All Categories</option>
<?php foreach ($categories as $c) { $sel = $c===$category ? 'selected' : ''; ?>
<option <?php echo $sel; ?> value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
<?php } ?>
</select>
</div>
<div class="col-md-2">
<select class="form-select" name="availability">
<option value="">Any</option>
<option value="available" <?php echo $availability==='available'?'selected':''; ?>>Available</option>
</select>
</div>
<div class="col-md-2"><button class="btn btn-primary w-100">Search</button></div>
</form>
<div class="row mt-4">
<?php foreach ($results as $b) { $cover = $b['cover_image'] ?? ''; ?>
<div class="col-md-4 mb-3">
<div class="card card-hover gradient-card h-100">
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($b['title']); ?></h5>
<div><?php echo htmlspecialchars($b['author']); ?></div>
<div class="text-muted"><?php echo htmlspecialchars($b['category']); ?></div>
<div>Available: <?php echo (int)$b['available_copies']; ?></div>
<div class="mt-2 d-flex align-items-center gap-2">
<?php if ($cover) { ?><img class="cover-thumb" src="<?php echo htmlspecialchars($cover); ?>" alt="cover"><?php } else { ?><div class="cover-placeholder">BK</div><?php } ?>
 </div>
<a class="btn btn-outline-primary mt-2" href="?route=book&id=<?php echo (int)$b['id']; ?>">View</a>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</body>
</html>
