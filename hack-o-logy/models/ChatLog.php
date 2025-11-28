<?php
class ChatLog {
 static function create($user_id,$question,$answer) {
  $stmt = Db::conn()->prepare('INSERT INTO chat_logs (user_id,question,answer) VALUES (?,?,?)');
  $stmt->execute([$user_id,$question,$answer]);
 }
 static function all($limit=50) {
  $limit = intval($limit);
  $stmt = Db::conn()->query('SELECT cl.*, u.name FROM chat_logs cl LEFT JOIN users u ON u.id=cl.user_id ORDER BY cl.id DESC LIMIT '.$limit);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function search($q,$limit=50) {
  $qLike = '%'.$q.'%';
  $limit = intval($limit);
  $sql = 'SELECT cl.*, u.name FROM chat_logs cl LEFT JOIN users u ON u.id=cl.user_id WHERE cl.question LIKE ? OR cl.answer LIKE ? ORDER BY cl.id DESC LIMIT '.$limit;
  $stmt = Db::conn()->prepare($sql);
  $stmt->execute([$qLike,$qLike]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
