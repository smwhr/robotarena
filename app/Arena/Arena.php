<?php
namespace Arena;

class Arena{

  private $board;
  private $robots = ["A"=>["bot"=>null, "position"=>null], "B"=>["bot"=>null, "position"=>null]];

  public function __construct($ascii){
    $this->board = [];
    $j = 0;
    foreach(explode("\n", $ascii) as $line){
      $i = 0;
      $l = [];
      foreach (str_split($line) as $char) {
        if(in_array($char, ["A","B"])){
          $this->robots[$char]["position"] = new RobotPosition($i, $j);
          $char = ".";
        }
        $l[] = $char;
        $i++;
      }
      $this->board[] = $l;
      $j++;
    }
  }
  public function loadRobots(array $robots){
    list($robotA, $robotB) = $robots;
    $this->robots["A"]["bot"] = $robotA;
    $this->robots["B"]["bot"] = $robotB;
  }

  public function getSize(){
    return [count($this->board[0]), count($this->board)];
  }

  public function charAtPosition($x, $y){
    foreach($this->robots as &$r){
      if($r["position"]->x == $x && $r["position"]->y == $y){
        return "R";
      }
    }
    return $this->board[$y][$x];
  }

  public function charAround($x, $y){
    $data = []
    for($j = $y -2 ; $j <= $y +2 ; $j++){
      $l = [];
      for($i = $x -2 ; $j <= $x +2 ; $x++){
        $l[] = $this->charAtPosition($i,$j);
      }
      $data[] = $l;
    }

    return $data;
  }

  public function canEnter($x, $y){
    return $this->charAtPosition($x, $y) == ".";
  }

  public function turn(){
    foreach ($this->robots as &$r) {
      # code...
    }

  }

}
