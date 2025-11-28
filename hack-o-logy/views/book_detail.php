<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Book Details</title>
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
<?php if ($msg) { ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php } ?>
<?php if ($err) { ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php } ?>
<div class="row">
<div class="col-md-8">
<div class="card">
<div class="card-body">
<h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
<div>Author: <?php echo htmlspecialchars($book['author']); ?></div>
<div>Category: <?php echo htmlspecialchars($book['category']); ?></div>
<div>ISBN: <?php echo htmlspecialchars($book['isbn']); ?></div>
<div>Shelf: <?php echo htmlspecialchars($book['shelf_location']); ?></div>
<div class="mt-2 d-flex align-items-center gap-3">
<?php if (!empty($book['cover_image'])) { ?><img class="cover-thumb" src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="cover"><?php } ?>
<span>Status: <?php echo $book['available_copies']>0?'Available':'Issued/Reserved'; ?></span>
</div>
<form class="mt-3" method="post" action="?route=issue">
<input type="hidden" name="book_id" value="<?php echo (int)$book['id']; ?>">
<button class="btn btn-primary" <?php echo $book['available_copies']>0?'':'disabled'; ?>>Issue</button>
</form>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card">
<div class="card-body">
<h5>Ask Assistant</h5>
<form method="post" action="?route=assistant">
<div class="mb-2"><input class="form-control" name="question" placeholder="Ask about this or related books"></div>
<button class="btn btn-outline-primary">Ask</button>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
