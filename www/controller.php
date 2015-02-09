<?php 
session_start();
require "../vendor/autoload.php";


if(isset($_POST['RESET']) || isset($_GET['RESET'])){
  unset($_SESSION['arena']);
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit;
}

$ascii_board = <<<EOF
xxxxxxxxxxxxxxxxxxxxxx
xxxxxxxxxxxxxxxxxxxxxx
xx..................xx
xx..................xx
xx..A...............xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..................xx
xx..............B...xx
xx..................xx
xxxxxxxxxxxxxxxxxxxxxx
xxxxxxxxxxxxxxxxxxxxxx
EOF;


if(!isset($_SESSION['arena'])){
  $arena = new Arena\Arena($ascii_board);
  $robotA = new Robot\DefaultRobot("A");
  $robotB = new Robot\DefaultRobot("B");
  $arena->loadRobots([$robotA,$robotB]);
}else{
  $arena = unserialize($_SESSION['arena']);
}

$turn_report = $arena->turn();


//don't forget to save to session the new state
$_SESSION['arena'] = serialize($arena);