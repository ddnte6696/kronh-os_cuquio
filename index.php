<?php
  define('TARGET', 'kronh-os');
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  $_SESSION['ubi']=TARGET;
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.TARGET.'/lib/config.php';
  if(!isset($_SESSION[$_SESSION['ubi']]['id'])){
    $_SESSION[$_SESSION['ubi']]['title']=TITLE;
  }
  
  define('CONTENT_TYPE', 'text/html');
  header('Content-Type: ' . CONTENT_TYPE);
  require_once 'view/header.php';
  $usuario = isset($_SESSION[$_SESSION['ubi']]['id']) ? $_SESSION[$_SESSION['ubi']]['id'] : null;
  if ($usuario) {
    require_once 'view/body.php';
  } else {
    require_once 'view/login.php';
  }
?>