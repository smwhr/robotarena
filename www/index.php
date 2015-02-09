<?php 

require "../vendor/autoload.php";

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

$arena = new Arena\Arena($ascii_board);

$robotA = new Robot\DefaultRobot("A");
$robotB = new Robot\DefaultRobot("B");
$arena->loadRobots([$robotA,$robotB]);
var_dump($arena);