<?php 
session_start();
require __DIR__."/../vendor/autoload.php";


if(isset($_POST['RESET']) || isset($_GET['RESET'])){
  unset($_SESSION['arena']);
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit;
}

include "board.php";

if(!isset($_SESSION['arena'])){
  $arena = new Arena\Arena($ascii_board);
  $robotA = new Robot\DefaultRobot("A");
  $robotB = new Robot\MadShooter("B");
  $arena->loadRobots([$robotA,$robotB]);
}else{
  $arena = unserialize($_SESSION['arena']);
}

try{
  $turn_report = $arena->turn();
}catch(Arena\WinningCondition $wc){
  $turn_report[] = $wc->getMessage();
}


//don't forget to save to session the new state
$_SESSION['arena'] = serialize($arena);