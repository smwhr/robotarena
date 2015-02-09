<?php
namespace Arena;

class Arena{

  public $board;
  public $robots = ["A"=>["bot"=>null, 
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
          $this->robots[$char]["position"] = new RobotPosition($i, $j, "N");
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

  public function charAtPosition($x, $y, $graphical = false){
    foreach($this->robots as $name => &$r){
      if($r["position"]->x == $x && $r["position"]->y == $y){
        if(!$graphical) return $name;
        switch($r["position"]->direction){
          case "N": $player = "&uarr;"; break;
          case "S": $player = "&darr;"; break;
          case "E": $player = "&rarr;"; break;
          case "W": $player = "&larr;"; break;
        }
        return "<span class='player".$r['bot']->name."'>".$player."</span>";
      }
    }
    return $this->board[$y][$x];
  }

  public function charAround($x, $y, $graphical = false){
    $data = [];
    for($j = $y -2 ; $j <= $y +2 ; $j++){
      $l = [];
      for($i = $x -2 ; $i <= $x +2 ; $i++){
        $l[] = $this->charAtPosition($i,$j,$graphical);
      }
      $data[] = $l;
    }

    return $data;
  }

  public function canEnter($x, $y){
    return $this->charAtPosition($x, $y) == ".";
  }

  public function bullet($bulletPosition){
    list($bx, $by) = $bulletPosition->ahead();
    while($this->canEnter($bx, $by)){
      $bulletPosition->commitMove();
      list($bx, $by) = $bulletPosition->ahead();
    }
    $obstacle = $this->charAtPosition($bx, $by);
    if($obstacle == "x"){
      return "Bullet hit a wall";
    }else{
      $this->robots[$obstacle]["life"]--;
      switch ($bulletPosition->direction) {
        case "N":
          $from = "S";
          break;
        case "S":
          $from = "N";
          break;
        case "E":
          $from = "W";
          break;
        case "W":
          $from = "E";
          break;
      }
      $this->robots[$obstacle]["bot"]->notifyEnnemy($from);
      return $obstacle." hit from ".$from;
    }
  }

  public function turn(){
    $report = [];
    foreach ($this->robots as $name => &$r) {
      $rx = $r["position"]->x;
      $ry = $r["position"]->y;
      $rdir = $r["position"]->direction;
      $r["bot"]->notifyPosition($rx,$ry, $rdir);
      $r["bot"]->notifySurroundings($this->charAround($rx,$rx));
      switch($r["bot"]->decide()){
        case RobotOrder::TURN_LEFT:
          $r["position"]->rotate('left');
          $report[] = $name." turned left";
          break;
        case RobotOrder::TURN_RIGHT:
          $r["position"]->rotate('right');
          $report[] = $name." turned right";
          break;
        case RobotOrder::AHEAD:
          list($nx, $ny) = $r["position"]->ahead();
          if($this->canEnter($nx, $ny)) {
            $r["position"]->commitMove();
            $report[] = $name." went ahead";
          }else{
            $r["position"]->rollbackMove();
            $report[] = $name." was blocked";
          }
          break;
        case RobotOrder::FIRE:
          $report[] = $name." shooted";
          $report[] = $this->bullet($r["position"]->copy());
          break;
      }
    }
    return $report;
  }

}
