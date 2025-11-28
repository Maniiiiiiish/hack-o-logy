<?php $name = htmlspecialchars($u['name']); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>User Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand" href="?route=user/dashboard">Smart Library</a>
<div class="ms-auto">
<a class="btn btn-light" href="?route=logout">Logout</a>
</div>
</div>
</nav>
<div class="container py-4">
<h3 class="mb-3">Welcome <?php echo $name; ?></h3>
<div class="assistant-hero"><h2>What's on your mind today?</h2></div>
<div class="assistant-bar mb-3">
 <div class="input-row">
  <input id="assistant-inline-input" placeholder="Ask anything" />
  <button class="voice-pill" id="assistant-voice-toggle">Voice</button>
  <button class="btn btn-primary" id="assistant-inline-send">Send</button>
 </div>
 <div class="assistant-chips mt-2">
  <div class="chip" data-action="attach">Attach</div>
  <div class="chip" data-action="search">Search</div>
  <div class="chip" data-action="study">Study</div>
 </div>
</div>
<div id="assistant-inline-messages" class="assistant-inline"></div>
<div class="row g-3">
 <div class="col-md-6">
  <div class="card card-hover gradient-card">
   <div class="card-body">
    <h5 class="card-title">Search Books</h5>
    <a href="?route=search" class="btn btn-outline-primary">Open</a>
   </div>
  </div>
 </div>
 <div class="col-md-6">
  <div class="card card-hover gradient-card">
   <div class="card-body">
    <h5 class="card-title">My Books</h5>
    <a href="?route=my_books" class="btn btn-outline-primary">Open</a>
   </div>
  </div>
 </div>
 </div>
</div>
<script src="assets/assistant_inline.js"></script>
</body>
</html>
