<?php
class Auth {
 static function user() {
  if (!isset($_SESSION['user_id'])) return null;
  return User::findById($_SESSION['user_id']);
 }
 static function login($email,$password) {
  $u = User::findByEmail($email);
  if (!$u) return false;
  if (isset($u['active']) && !$u['active']) return false;
  if (!password_verify($password, $u['password_hash'])) return false;
  $_SESSION['user_id'] = $u['id'];
  return true;
 }
 static function register($name,$email,$password,$role='user') {
  $existing = User::findByEmail($email);
  if ($existing) return false;
  $id = User::create($name,$email,$password,$role);
  $_SESSION['user_id'] = $id;
  return true;
 }
 static function requireRole($role) {
  $u = self::user();
  if (!$u) return false;
  return $u['role'] === $role;
 }
 static function logout() {
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
   $params = session_get_cookie_params();
   setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
 }
}
