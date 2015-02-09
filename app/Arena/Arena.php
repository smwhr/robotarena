<?php
namespace Arena;

class Arena{

  private $board;
  private $robots = ["A"=>["bot"=>null, 
                           "position"=>null,
                           "life" => 10], 
                     "B"=>["bot"=>null, 
                           "position"=>null,
                           "life" => 10]
                    ];

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
    $data = [];
    for($j = $y -2 ; $j <= $y +2 ; $j++){
      $l = [];
      for($i = $x -2 ; $i <= $x +2 ; $i++){
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
      $rx = $r["position"]->x;
      $ry = $r["position"]->y;
      $r["bot"]->notifyPosition($rx,$ry);
      $r["bot"]->notifySurroundings($this->charAround($rx,$rx));
      switch($r["bot"]->decide()){
        case RobotOrder::TURN_LEFT:
          $r["position"]->rotate('left');
          break;
        case RobotOrder::TURN_RIGHT:
          $r["position"]->rotate('right');
          break;
        case RobotOrder::AHEAD:
          $r["position"]->ahead();
          if($this->canEnter($r["position"]->x,$r["position"]->y)){
            $r["position"]->commitMove();
          }else{
            $r["position"]->rollbackMove();
          }
          break;
        case RobotOrder::FIRE:
          break;
      }
    }

  }

}
