<?php
class IssuedBook {
 static function issue($user_id,$book_id,$days=14) {
  $ok = Book::decrementAvailable($book_id);
  if (!$ok) return false;
  $issue_date = date('Y-m-d');
  $due_date = date('Y-m-d', strtotime('+'.$days.' days'));
  $stmt = Db::conn()->prepare('INSERT INTO issued_books (user_id,book_id,issue_date,due_date,status) VALUES (?,?,?,?,\'issued\')');
  $stmt->execute([$user_id,$book_id,$issue_date,$due_date]);
  return Db::conn()->lastInsertId();
 }
 static function returnBook($user_id,$id) {
  $stmt = Db::conn()->prepare('SELECT * FROM issued_books WHERE id=? AND user_id=? AND status=\'issued\'');
  $stmt->execute([$id,$user_id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) return false;
  $stmt2 = Db::conn()->prepare('UPDATE issued_books SET status=\'returned\', return_date=? WHERE id=?');
  $stmt2->execute([date('Y-m-d'),$id]);
  Book::incrementAvailable($row['book_id']);
  return true;
 }
 static function userIssued($user_id) {
  $stmt = Db::conn()->prepare('SELECT ib.*, b.title, b.author FROM issued_books ib JOIN books b ON b.id = ib.book_id WHERE ib.user_id=? ORDER BY ib.issue_date DESC');
  $stmt->execute([$user_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function countIssued() {
  $stmt = Db::conn()->query('SELECT COUNT(*) FROM issued_books WHERE status=\'issued\'');
  return (int)$stmt->fetchColumn();
 }
 static function countReturned() {
  $stmt = Db::conn()->query('SELECT COUNT(*) FROM issued_books WHERE status=\'returned\'' );
  return (int)$stmt->fetchColumn();
 }
 static function recentActivities($limit=10) {
  $limit = intval($limit);
  $stmt = Db::conn()->query('SELECT ib.*, b.title, u.name FROM issued_books ib JOIN books b ON b.id=ib.book_id JOIN users u ON u.id=ib.user_id ORDER BY ib.id DESC LIMIT '.$limit);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function issuesPerDay($days=7) {
  $days = intval($days);
  $stmt = Db::conn()->query("SELECT issue_date as d, COUNT(*) c FROM issued_books WHERE issue_date >= DATE_SUB(CURDATE(), INTERVAL ${days} DAY) GROUP BY issue_date ORDER BY issue_date");
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function mostIssuedBooks($limit=5) {
  $limit = intval($limit);
  $stmt = Db::conn()->query('SELECT b.title, COUNT(*) as cnt FROM issued_books ib JOIN books b ON b.id=ib.book_id GROUP BY b.id ORDER BY cnt DESC LIMIT '.$limit);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
