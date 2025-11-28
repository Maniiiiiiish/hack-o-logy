<?php
session_start();
require __DIR__.'/lib/Db.php';
require __DIR__.'/models/User.php';
require __DIR__.'/lib/Auth.php';
require __DIR__.'/models/Book.php';
require __DIR__.'/models/IssuedBook.php';
require __DIR__.'/models/ChatLog.php';
require __DIR__.'/lib/AI.php';
$route = isset($_GET['route']) ? $_GET['route'] : '';
$method = $_SERVER['REQUEST_METHOD'];
if ($route === '' || $route === 'home') {
 $u = Auth::user();
 if ($u) {
  if ($u['role'] === 'admin') { header('Location: ?route=admin/dashboard'); exit; }
  header('Location: ?route=user/dashboard'); exit;
 }
 header('Location: ?route=login'); exit;
}
if ($route === 'login') {
 if ($method === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  if (Auth::login($email,$password)) {
   $u = Auth::user();
   if ($u['role'] === 'admin') { header('Location: ?route=admin/dashboard'); exit; }
   header('Location: ?route=user/dashboard'); exit;
  }
  $error = 'Invalid credentials';
  include __DIR__.'/views/login.php';
  exit;
 }
 include __DIR__.'/views/login.php';
 exit;
}
if ($route === 'admin/login') {
 if ($method === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  if (Auth::login($email,$password)) {
   $u = Auth::user();
   if ($u['role'] === 'admin') { header('Location: ?route=admin/dashboard'); exit; }
   Auth::logout();
   $error = 'Not an admin account';
   include __DIR__.'/views/admin_login.php';
   exit;
  }
  $error = 'Invalid admin credentials';
  include __DIR__.'/views/admin_login.php';
  exit;
 }
 include __DIR__.'/views/admin_login.php';
 exit;
}
if ($route === 'register') {
 if ($method === 'POST') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  if (Auth::register($name,$email,$password,'user')) {
   header('Location: ?route=user/dashboard'); exit;
  }
  $error = 'Email already exists';
  include __DIR__.'/views/register.php';
  exit;
 }
 include __DIR__.'/views/register.php';
 exit;
}
if ($route === 'logout') {
 Auth::logout();
 header('Location: ?route=login'); exit;
}
if ($route === 'user/dashboard') {
 if (!Auth::requireRole('user')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 include __DIR__.'/views/user_dashboard.php';
 exit;
}
if ($route === 'admin/dashboard') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $totalBooks = (int)Db::conn()->query('SELECT COUNT(*) FROM books')->fetchColumn();
 $totalUsers = (int)Db::conn()->query('SELECT COUNT(*) FROM users')->fetchColumn();
 $issuedCount = IssuedBook::countIssued();
 $returnedCount = IssuedBook::countReturned();
 $popularCategories = Book::popularCategories();
 $mostIssued = IssuedBook::mostIssuedBooks(5);
 $recent = IssuedBook::recentActivities(10);
 $issuesPerDay = IssuedBook::issuesPerDay(7);
 $registrations = User::registrationsPerMonth(6);
 include __DIR__.'/views/admin_dashboard.php';
 exit;
}
if ($route === 'search') {
 if (!Auth::requireRole('user')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $q = $_GET['q'] ?? '';
 $category = $_GET['category'] ?? '';
 $availability = $_GET['availability'] ?? '';
 $results = Book::search($q,$category,$availability);
 $curated = [
  'Fiction','Non-Fiction','Literature','Poetry','History','Geography','Philosophy','Psychology','Sociology','Anthropology',
  'Politics','Economics','Business','Law','Education','Religion','Art','Music','Design','Architecture','Journalism','Media Studies',
  'Science','Mathematics','Physics','Chemistry','Biology','Environmental Science','Astronomy','Computer Science','Engineering',
  'Technology','Artificial Intelligence','Data Science','Health','Medicine','Nursing','Pharmacy','Public Health','Sports','Travel',
  'Self-Help','Children','Young Adult','Cookbook','Languages','Linguistics'
 ];
 $dbCats = array_map(function($b){return $b['category'];}, Book::all());
 $categories = array_values(array_unique(array_merge($curated,$dbCats)));
 sort($categories);
 include __DIR__.'/views/search.php';
 exit;
}
if ($route === 'book') {
 if (!Auth::requireRole('user')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
 $book = Book::find($id);
 if (!$book) { http_response_code(404); echo 'Not Found'; exit; }
 $msg = $_GET['msg'] ?? '';
 $err = $_GET['err'] ?? '';
 include __DIR__.'/views/book_detail.php';
 exit;
}
if ($route === 'issue') {
 if ($method === 'POST' && Auth::requireRole('user')) {
  $u = Auth::user();
  $book_id = intval($_POST['book_id'] ?? 0);
  $ok = IssuedBook::issue($u['id'],$book_id);
  if ($ok) { header('Location: ?route=book&id='.$book_id.'&msg=Issued'); exit; }
  header('Location: ?route=book&id='.$book_id.'&err=Not+available'); exit;
 }
 header('Location: ?route=login'); exit;
}
if ($route === 'return') {
 if ($method === 'POST' && Auth::requireRole('user')) {
  $u = Auth::user();
  $issue_id = intval($_POST['issue_id'] ?? 0);
  IssuedBook::returnBook($u['id'],$issue_id);
  header('Location: ?route=my_books'); exit;
 }
 header('Location: ?route=login'); exit;
}
if ($route === 'my_books') {
 if (!Auth::requireRole('user')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $items = IssuedBook::userIssued($u['id']);
 include __DIR__.'/views/my_books.php';
 exit;
}
if ($route === 'assistant') {
 if (!Auth::requireRole('user')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $question = '';
 $answer = '';
 $suggestions = [];
 if ($method === 'POST') {
  $question = trim($_POST['question'] ?? '');
  if ($question !== '') {
   $lower = strtolower($question);
   if (str_contains($lower,'time')) { $answer = 'Library timings: Mon-Fri 9:00–17:00'; }
   if (str_contains($lower,'where')) {
    $parts = explode('where', $lower);
    $q = trim(end($parts));
    $suggestions = Book::search($q,'','');
    if (!$answer) { $answer = 'Here are matching books'; }
   } else {
    $suggestions = Book::search($question,'','');
    if (!$answer) { $answer = 'Recommendations based on your query'; }
   }
   ChatLog::create($u['id'],$question,$answer);
  }
 }
 include __DIR__.'/views/assistant.php';
 exit;
}
if ($route === 'assistant_api') {
 if (!(Auth::requireRole('user') || Auth::requireRole('admin'))) { http_response_code(401); header('Content-Type: application/json'); echo json_encode(['error'=>'unauthorized']); exit; }
 $u = Auth::user();
 $input = file_get_contents('php://input');
 $json = json_decode($input,true);
 $q = '';
 if (is_array($json) && isset($json['question'])) { $q = trim($json['question']); }
 if ($q==='') { $q = trim($_POST['question'] ?? ''); }
 $answer = '';
 $suggestions = [];
 if ($q!=='') {
  $lower = strtolower($q);
  if (str_contains($lower,'time')) { $answer = 'Library timings: Mon-Fri 9:00–17:00'; }
  try {
   $suggestions = Book::recommendForUser($u['id'],$q,20);
   if (str_contains($lower,'my books') || str_contains($lower,'my issued') || str_contains($lower,'my loans')) {
    $issued = IssuedBook::userIssued($u['id']);
    $suggestions = [];
    foreach ($issued as $it) {
     $b = Book::find($it['book_id']);
     if ($b) { $suggestions[] = $b; }
    }
    if (count($issued) > 0) { $answer = 'Here are your current issued books'; }
   }
   if (str_contains($lower,'overdue') || str_contains($lower,'due')) {
    $issued = IssuedBook::userIssued($u['id']);
    $dueSoon = [];
    $today = date('Y-m-d');
    foreach ($issued as $it) {
     if (!empty($it['due_date']) && $it['status'] === 'issued') {
      if ($it['due_date'] <= $today) { $dueSoon[] = $it; }
     }
    }
    if (count($dueSoon) > 0) { $answer = 'You have items due or overdue'; }
   }
  } catch (Throwable $e) {
   $suggestions = [];
  }
  $ctx = '';
  foreach (array_slice($suggestions,0,5) as $b) { $ctx .= $b['title'].' by '.$b['author'].' • '.$b['category'].' • shelf '.$b['shelf_location']."\n"; }
  $aiPrompt = "You are a helpful assistant for a university smart library portal.\n"
   ."Always answer clearly. If the question is about general knowledge, math, or non-library topics, solve it directly (show steps for math).\n"
   ."If the question is about books or the library, combine database suggestions and shelf/location to guide the user and prioritize books that match the user’s past interests.\n"
   ."Use concise sentences and avoid generic phrases.\n"
   ."Question: ".$q;
  $ai = AI::generate($aiPrompt, $ctx);
  if ($ai) {
   $answer = $ai;
   if (count($suggestions) > 0) {
    $top = array_slice($suggestions,0,3);
    $lines = [];
    foreach ($top as $b) {
     $lines[] = $b['title'].' by '.$b['author'].' ('.$b['category'].') • shelf '.$b['shelf_location'].' • avail '.$b['available_copies'];
    }
    $answer = rtrim($answer);
    $answer .= ' Top picks: '.implode('; ', $lines);
   }
  }
  if (!$ai) {
   if (count($suggestions) > 0) {
    $count = count($suggestions);
    $top = array_slice($suggestions,0,3);
    $lines = [];
    foreach ($top as $b) {
     $lines[] = $b['title'].' by '.$b['author'].' ('.$b['category'].') • shelf '.$b['shelf_location'].' • avail '.$b['available_copies'];
    }
    $answer = 'Found '.$count.' books for "'.$q.'". Top picks: '.implode('; ', $lines);
   } else {
    $cats = Book::popularCategories();
    $names = array_map(function($x){ return $x['category']; }, $cats);
    $answer = 'No matches for "'.$q.'". Try categories: '.implode(', ', $names);
   }
  }
  try { ChatLog::create($u['id'],$q,$answer); } catch (Throwable $e) { /* ignore logging failures */ }
 }
 header('Content-Type: application/json');
 echo json_encode(['answer'=>$answer,'suggestions'=>$suggestions]);
 exit;
}
if ($route === 'admin/users') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $users = User::all();
 include __DIR__.'/views/admin_users.php';
 exit;
}
if ($route === 'admin/users/edit') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $id = intval($_GET['id'] ?? 0);
 $user = User::findById($id);
 if ($method === 'POST') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $role = $_POST['role'] ?? 'user';
  User::updateBasic($id,$name,$email,$role);
  header('Location: ?route=admin/users'); exit;
 }
 include __DIR__.'/views/admin_user_edit.php';
 exit;
}
if ($route === 'admin/users/toggle') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $id = intval($_POST['id'] ?? 0);
 $active = intval($_POST['active'] ?? 1);
 User::setActive($id,$active);
 header('Location: ?route=admin/users'); exit;
}
if ($route === 'admin/users/reset') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $id = intval($_POST['id'] ?? 0);
 $new = $_POST['new_password'] ?? '';
 if ($new==='') { $new = bin2hex(random_bytes(4)); }
 User::resetPassword($id,$new);
 header('Location: ?route=admin/users'); exit;
}
if ($route === 'admin/issue') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $users = User::all();
 $books = Book::all();
 $message = '';
 if ($method === 'POST') {
  $user_id = intval($_POST['user_id'] ?? 0);
  $book_id = intval($_POST['book_id'] ?? 0);
  $ok = IssuedBook::issue($user_id,$book_id);
  $message = $ok ? 'Issued successfully' : 'Not available';
 }
 include __DIR__.'/views/admin_issue.php';
 exit;
}
if ($route === 'admin/return') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $items = Db::conn()->query('SELECT ib.id, b.title, u.name, ib.status FROM issued_books ib JOIN books b ON b.id=ib.book_id JOIN users u ON u.id=ib.user_id ORDER BY ib.id DESC LIMIT 50')->fetchAll(PDO::FETCH_ASSOC);
 $message = '';
 if ($method === 'POST') {
  $issue_id = intval($_POST['issue_id'] ?? 0);
  $row = Db::conn()->prepare('SELECT * FROM issued_books WHERE id=?');
  $row->execute([$issue_id]);
  $d = $row->fetch(PDO::FETCH_ASSOC);
  if ($d && $d['status']==='issued') { IssuedBook::returnBook($d['user_id'],$issue_id); $message='Returned'; }
 }
 include __DIR__.'/views/admin_return.php';
 exit;
}
if ($route === 'admin/chatlogs') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $q = $_GET['q'] ?? '';
 $logs = $q !== '' ? ChatLog::search($q,100) : ChatLog::all(100);
 include __DIR__.'/views/admin_chatlogs.php';
 exit;
}
if ($route === 'admin/books') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $u = Auth::user();
 $list = Book::all();
 include __DIR__.'/views/admin_books.php';
 exit;
}
if ($route === 'admin/books/create') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 if ($method === 'POST') {
  $data = [
   'title'=>$_POST['title'] ?? '',
   'author'=>$_POST['author'] ?? '',
   'category'=>$_POST['category'] ?? '',
   'isbn'=>$_POST['isbn'] ?? '',
   'shelf_location'=>$_POST['shelf_location'] ?? '',
   'total_copies'=>intval($_POST['total_copies'] ?? 1),
   'available_copies'=>intval($_POST['available_copies'] ?? 1)
  ];
  if (isset($_FILES['cover']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {
   $dir = __DIR__.'/uploads/covers'; if (!is_dir($dir)) { mkdir($dir, 0777, true); }
   $name = time().'_'.preg_replace('/[^A-Za-z0-9_.-]/','_', $_FILES['cover']['name']);
   $dest = $dir.'/'.$name;
   if (move_uploaded_file($_FILES['cover']['tmp_name'],$dest)) { $data['cover_image'] = 'uploads/covers/'.$name; }
  }
  Book::create($data);
  header('Location: ?route=admin/books'); exit;
 }
 include __DIR__.'/views/admin_books.php';
 exit;
}
if ($route === 'admin/books/edit') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $id = intval($_GET['id'] ?? 0);
 $book = Book::find($id);
 if ($method === 'POST') {
  $data = [
   'title'=>$_POST['title'] ?? '',
   'author'=>$_POST['author'] ?? '',
   'category'=>$_POST['category'] ?? '',
   'isbn'=>$_POST['isbn'] ?? '',
   'shelf_location'=>$_POST['shelf_location'] ?? '',
   'total_copies'=>intval($_POST['total_copies'] ?? 1),
   'available_copies'=>intval($_POST['available_copies'] ?? 1)
  ];
  if (isset($_FILES['cover']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {
   $dir = __DIR__.'/uploads/covers'; if (!is_dir($dir)) { mkdir($dir, 0777, true); }
   $name = time().'_'.preg_replace('/[^A-Za-z0-9_.-]/','_', $_FILES['cover']['name']);
   $dest = $dir.'/'.$name;
   if (move_uploaded_file($_FILES['cover']['tmp_name'],$dest)) { $data['cover_image'] = 'uploads/covers/'.$name; }
  } else { $data['cover_image'] = $book['cover_image'] ?? null; }
  Book::update($id,$data);
  header('Location: ?route=admin/books'); exit;
 }
 include __DIR__.'/views/admin_books.php';
 exit;
}
if ($route === 'admin/settings') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 $me = Auth::user();
 $msg = '';
 if ($method === 'POST') {
  $name = $_POST['name'] ?? $me['name'];
  $email = $_POST['email'] ?? $me['email'];
  User::updateBasic($me['id'],$name,$email,$me['role']);
  if (!empty($_POST['password'])) { User::resetPassword($me['id'], $_POST['password']); }
  $msg = 'Profile updated';
  $me = User::findById($me['id']);
 }
 include __DIR__.'/views/admin_settings.php';
 exit;
}
if ($route === 'admin/books/delete') {
 if (!Auth::requireRole('admin')) { header('Location: ?route=login'); exit; }
 if ($method === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  Book::delete($id);
  header('Location: ?route=admin/books'); exit;
 }
 header('Location: ?route=admin/books'); exit;
}
http_response_code(404);
echo 'Not Found';

