<?php 
require __DIR__."/../vendor/autoload.php";
$robotAClass = "Robot\\".$argv[1];
$robotBClass = "Robot\\".$argv[2];

include __DIR__."/../www/board.php";

$arena = new Arena\Arena($ascii_board);
$robotA = new $robotAClass("A");
$robotB = new $robotBClass("B");
$arena->loadRobots([$robotA,$robotB]);

echo "Starting fight between ".$robotAClass." and ".$robotBClass." ! \n";
$i = 0;
while(true){
  echo $i++."\r";
  try{
    $arena->turn();
  }catch(Arena\WinningCondition $wc){
    echo "\n".$wc->getMessage()."\n";
    break;
  }
}