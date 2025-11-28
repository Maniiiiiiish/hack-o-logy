<?php
class Book {
 static function all() {
  $stmt = Db::conn()->query('SELECT * FROM books ORDER BY title');
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function find($id) {
  $stmt = Db::conn()->prepare('SELECT * FROM books WHERE id = ?');
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
 }
 static function search($q,$category,$availability) {
  $sql = 'SELECT * FROM books WHERE 1=1';
  $params = [];
  if ($q !== '') {
   $sql .= ' AND (title LIKE ? OR author LIKE ? OR category LIKE ? OR isbn LIKE ? OR shelf_location LIKE ?)';
   $like = '%'.$q.'%';
   $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
  }
  if ($category !== '') {
   $sql .= ' AND category = ?';
   $params[] = $category;
  }
  if ($availability === 'available') {
   $sql .= ' AND available_copies > 0';
  }
  $sql .= ' ORDER BY title';
  $stmt = Db::conn()->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function create($data) {
  $stmt = Db::conn()->prepare('INSERT INTO books (title,author,category,isbn,shelf_location,total_copies,available_copies,cover_image) VALUES (?,?,?,?,?,?,?,?)');
  $stmt->execute([$data['title'],$data['author'],$data['category'],$data['isbn'],$data['shelf_location'],$data['total_copies'],$data['available_copies'],$data['cover_image'] ?? null]);
  return Db::conn()->lastInsertId();
 }
 static function update($id,$data) {
  $stmt = Db::conn()->prepare('UPDATE books SET title=?, author=?, category=?, isbn=?, shelf_location=?, total_copies=?, available_copies=?, cover_image=? WHERE id=?');
  return $stmt->execute([$data['title'],$data['author'],$data['category'],$data['isbn'],$data['shelf_location'],$data['total_copies'],$data['available_copies'],$data['cover_image'] ?? null,$id]);
 }
 static function popularCategories() {
  $stmt = Db::conn()->query('SELECT category, COUNT(*) as cnt FROM books GROUP BY category ORDER BY cnt DESC LIMIT 5');
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function userCategoryCounts($user_id) {
  $stmt = Db::conn()->prepare('SELECT b.category, COUNT(*) as cnt FROM issued_books ib JOIN books b ON b.id=ib.book_id WHERE ib.user_id=? GROUP BY b.category');
  $stmt->execute([$user_id]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $map = [];
  foreach ($rows as $r) { $map[$r['category']] = (int)$r['cnt']; }
  return $map;
 }
 static function recommendForUser($user_id,$q,$limit=20) {
  $list = self::search($q,'','');
  $weights = self::userCategoryCounts($user_id);
  foreach ($list as &$b) {
   $score = 0;
   $t = strtolower($b['title']);
   $a = strtolower($b['author']);
   $c = strtolower($b['category']);
   $qq = strtolower($q);
   if ($qq !== '') {
    if (strpos($t,$qq)!==false) $score += 3;
    if (strpos($a,$qq)!==false) $score += 2;
    if (strpos($c,$qq)!==false) $score += 2;
   }
   if (isset($weights[$b['category']])) { $score += 2 * $weights[$b['category']]; }
   if ((int)$b['available_copies'] > 0) { $score += 1; }
   $b['_score'] = $score;
  }
  usort($list, function($x,$y){ return $y['_score'] <=> $x['_score']; });
  return array_slice($list,0,$limit);
 }
 static function delete($id) {
  $stmt = Db::conn()->prepare('DELETE FROM books WHERE id=?');
  return $stmt->execute([$id]);
 }
 static function decrementAvailable($id) {
  $stmt = Db::conn()->prepare('UPDATE books SET available_copies = available_copies - 1 WHERE id=? AND available_copies > 0');
  $stmt->execute([$id]);
  return $stmt->rowCount() > 0;
 }
 static function incrementAvailable($id) {
  $stmt = Db::conn()->prepare('UPDATE books SET available_copies = available_copies + 1 WHERE id=?');
  $stmt->execute([$id]);
 }
 static function countAll() {
  return (int)Db::conn()->query('SELECT COUNT(*) FROM books')->fetchColumn();
 }
 static function seedCurated() {
  if (self::countAll() > 0) return;
  $cats = [
   'Fiction','Non-Fiction','Literature','Poetry','History','Geography','Philosophy','Psychology','Sociology','Anthropology',
   'Politics','Economics','Business','Law','Education','Religion','Art','Music','Design','Architecture','Journalism','Media Studies',
   'Science','Mathematics','Physics','Chemistry','Biology','Environmental Science','Astronomy','Computer Science','Engineering',
   'Technology','Artificial Intelligence','Data Science','Health','Medicine','Nursing','Pharmacy','Public Health','Sports','Travel',
   'Self-Help','Children','Young Adult','Cookbook','Languages','Linguistics'
  ];
  $pdo = Db::conn();
  $pdo->beginTransaction();
  foreach ($cats as $c) {
   $title = 'Introduction to '.$c;
   $author = 'Editorial Board';
   $isbn = substr(md5($c),0,13);
   $shelf = strtoupper(substr($c,0,1)).'-'.rand(1,20);
   $data = [
    'title'=>$title,
    'author'=>$author,
    'category'=>$c,
    'isbn'=>$isbn,
    'shelf_location'=>$shelf,
    'total_copies'=>5,
    'available_copies'=>5,
    'cover_image'=>null
   ];
   self::create($data);
  }
  $pdo->commit();
 }
}
