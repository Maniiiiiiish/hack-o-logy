<?php
class User {
 static function all() {
  $stmt = Db::conn()->query('SELECT id,name,email,role,active,created_at FROM users ORDER BY created_at DESC');
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 static function findByEmail($email) {
  $stmt = Db::conn()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
 }
 static function findById($id) {
  $stmt = Db::conn()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
 }
 static function create($name,$email,$password,$role) {
  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = Db::conn()->prepare('INSERT INTO users (name,email,password_hash,role,created_at) VALUES (?,?,?,?,NOW())');
  $stmt->execute([$name,$email,$hash,$role]);
  return Db::conn()->lastInsertId();
 }
 static function updateBasic($id,$name,$email,$role) {
  $stmt = Db::conn()->prepare('UPDATE users SET name=?, email=?, role=? WHERE id=?');
  return $stmt->execute([$name,$email,$role,$id]);
 }
 static function setActive($id,$active) {
  $stmt = Db::conn()->prepare('UPDATE users SET active=? WHERE id=?');
  return $stmt->execute([$active,$id]);
 }
 static function resetPassword($id,$newPassword) {
  $hash = password_hash($newPassword, PASSWORD_DEFAULT);
  $stmt = Db::conn()->prepare('UPDATE users SET password_hash=? WHERE id=?');
  return $stmt->execute([$hash,$id]);
 }
 static function registrationsPerMonth($months=6) {
  $months = intval($months);
  $sql = "SELECT DATE_FORMAT(created_at,'%Y-%m') as m, COUNT(*) c FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL $months MONTH) GROUP BY m ORDER BY m";
  $stmt = Db::conn()->query($sql);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
