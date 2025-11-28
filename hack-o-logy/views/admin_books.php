<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<div class="row">
<div class="col-md-6">
<h4 class="mb-3">Books</h4>
<table class="table table-sm table-striped">
<thead><tr><th></th><th>Title</th><th>Author</th><th>Category</th><th>ISBN</th><th>Avail</th><th></th></tr></thead>
<tbody>
<?php foreach (($list ?? []) as $b) { $cover = $b['cover_image'] ?? ''; ?>
<tr>
<td><?php if ($cover) { ?><img class="cover-thumb" src="<?php echo htmlspecialchars($cover); ?>" alt="cover"><?php } else { ?><div class="cover-placeholder">BK</div><?php } ?></td>
<td><?php echo htmlspecialchars($b['title']); ?></td>
<td><?php echo htmlspecialchars($b['author']); ?></td>
<td><?php echo htmlspecialchars($b['category']); ?></td>
<td><?php echo htmlspecialchars($b['isbn']); ?></td>
<td><?php echo (int)$b['available_copies']; ?></td>
<td class="text-nowrap">
<a class="btn btn-sm btn-outline-primary" href="?route=admin/books/edit&id=<?php echo (int)$b['id']; ?>">Edit</a>
<form method="post" action="?route=admin/books/delete" style="display:inline">
<input type="hidden" name="id" value="<?php echo (int)$b['id']; ?>">
<button class="btn btn-sm btn-outline-danger">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<div class="col-md-6">
<h4 class="mb-3"><?php echo isset($book)?'Edit Book':'Add Book'; ?></h4>
<form method="post" enctype="multipart/form-data" action="<?php echo isset($book)?'?route=admin/books/edit&id='.(int)$book['id']:'?route=admin/books/create'; ?>">
<div class="mb-2"><input class="form-control" name="title" value="<?php echo isset($book)?htmlspecialchars($book['title']):''; ?>" placeholder="Title" required></div>
<div class="mb-2"><input class="form-control" name="author" value="<?php echo isset($book)?htmlspecialchars($book['author']):''; ?>" placeholder="Author" required></div>
<div class="mb-2"><input class="form-control" name="category" value="<?php echo isset($book)?htmlspecialchars($book['category']):''; ?>" placeholder="Category" required></div>
<div class="mb-2"><input class="form-control" name="isbn" value="<?php echo isset($book)?htmlspecialchars($book['isbn']):''; ?>" placeholder="ISBN" required></div>
<div class="mb-2"><input class="form-control" name="shelf_location" value="<?php echo isset($book)?htmlspecialchars($book['shelf_location']):''; ?>" placeholder="Shelf Location"></div>
<div class="mb-2"><input class="form-control" type="number" name="total_copies" value="<?php echo isset($book)?(int)$book['total_copies']:1; ?>" placeholder="Total Copies"></div>
<div class="mb-2"><input class="form-control" type="number" name="available_copies" value="<?php echo isset($book)?(int)$book['available_copies']:1; ?>" placeholder="Available Copies"></div>
<div class="mb-2">
<label class="form-label">Cover Image</label>
<input class="form-control" type="file" name="cover" accept="image/*">
<?php if (isset($book) && !empty($book['cover_image'])) { ?><img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="cover" style="max-height:80px" class="mt-2"><?php } ?>
</div>
<button class="btn btn-success"><?php echo isset($book)?'Save':'Add'; ?></button>
</form>
</div>
</div>
</div>
</body>
</html>
