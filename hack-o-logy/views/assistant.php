<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AI Assistant</title>
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
<h3 class="mb-3">Assistant</h3>
<form method="post" action="?route=assistant" class="mb-3">
<div class="input-group">
<input class="form-control" name="question" value="<?php echo htmlspecialchars($question); ?>" placeholder="Ask: timings, where is a book, recommend books">
<button class="btn btn-primary">Ask</button>
</div>
</form>
<?php if ($answer) { ?><div class="alert alert-info"><?php echo htmlspecialchars($answer); ?></div><?php } ?>
<div class="row">
<?php foreach ($suggestions as $b) { ?>
<div class="col-md-4 mb-3">
<div class="card h-100">
<div class="card-body">
<h5 class="card-title"><?php echo htmlspecialchars($b['title']); ?></h5>
<div><?php echo htmlspecialchars($b['author']); ?></div>
<div class="text-muted"><?php echo htmlspecialchars($b['category']); ?></div>
<a class="btn btn-outline-primary mt-2" href="?route=book&id=<?php echo (int)$b['id']; ?>">Open</a>
</div>
</div>
</div>
<?php } ?>
</div>
</div>
</body>
</html>
