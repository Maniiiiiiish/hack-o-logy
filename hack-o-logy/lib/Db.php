<?php
class Db {
 static $pdo;
 static $lastPingAt;
 static function connect() {
  $cfg = require __DIR__ . '/../config/config.php';
  $port = isset($cfg['db_port']) ? $cfg['db_port'] : '3306';
  $charset = 'utf8mb4';
  $dsn = 'mysql:host='.$cfg['db_host'].';port='.$port.';dbname='.$cfg['db_name'].';charset='.$charset;
  $opt = [
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES => false,
   PDO::ATTR_PERSISTENT => true
  ];
  self::$pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], $opt);
  self::$lastPingAt = time();
  return self::$pdo;
 }
 static function conn() {
  if (!self::$pdo) return self::connect();
  $now = time();
  if (!self::$lastPingAt || ($now - self::$lastPingAt) > 30) {
   try { self::$pdo->query('SELECT 1'); self::$lastPingAt = $now; }
   catch (PDOException $e) { return self::connect(); }
  }
  return self::$pdo;
 }
 static function ping() {
  try { self::conn()->query('SELECT 1'); return true; } catch (Throwable $e) { return false; }
 }
}
