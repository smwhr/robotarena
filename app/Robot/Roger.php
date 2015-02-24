<?php
namespace Robot;

use Arena\RobotOrder;

class Roger implements RobotInterface{
  public $turn;
  public $position;
  public $prefire;
  public $ennemyDir;
  public $data;
  public function __construct($name){
    $this->name = $name;
    $this->turn = 0;
    $this->prefire = 0.5;
  }

  public function notifyPosition(\Arena\RobotPosition $position){
    $this->position = $position;
  }
  public function notifySurroundings($data){
    $this->data = $data;

  }
  public function notifyEnnemy($direction){
    $this->ennemyDir = $direction;
  }
  public function decide(){
    $this->turn++;
    $orders = [RobotOrder::TURN_LEFT,
    RobotOrder::TURN_RIGHT,
    RobotOrder::AHEAD,
    RobotOrder::FIRE];
    foreach ($this->data as $key => $line ) {
      foreach($line as $value => $val){

      if(( ($val != "." ) or ($val != "x")) and ($val != $this->data[2][2])) {




            if (($this->data[0][2] == "A") or ($this->data[0][2] == 'B')){ return RobotOrder::FIRE; }

    if (($this->data[0][0] == 'A') or ($this->data[0][1] == 'B')) {

    if ($this->position->direction == 'E') { return RobotOrder::TURN_LEFT; }

    elseif ($this->position->direction == 'W') { return RobotOrder::TURN_RIGHT; }

    elseif ($this->position->direction == 'S') { return RobotOrder::TURN_RIGHT; }

    elseif($this->position->direction == 'N'){ return RobotOrder::FIRE; } }

    if (($this->data[0][3] == 'A') or ($this->data[0][4] == 'B')) {

    if ($this->position->direction == 'E') { return RobotOrder::TURN_LEFT; }

    elseif ($this->position->direction == 'W') { return RobotOrder::TURN_RIGHT; }

    elseif($this->position->direction == 'N'){ return RobotOrder::FIRE; }

    elseif($this->position->direction == 'S'){ return RobotOrder::TURN_RIGHT; } } 



        if((($this->data[2][0] !== ".") and ($this->data[2][0] !== "x")) or (($this->data[2][1] !== ".") and ($this->data[2][1] !== "x"))) { 
          if($this->position->direction === "W") { 
            return $orders[3]; 
          } 
          if($this->position->direction === "E") { 
            if($key[1][2] === ".") { 
              return $orders[0]; 
            } 
            else{ 
              return $orders[1]; 
            } } 
            else{ 
              return $orders[2]; 
            } } 
            if((($this->data[2][3] !== ".") and ($this->data[2][3] !== "x")) or (($this->data[2][4] !== ".") and ($this->data[2][4] !== "x"))) {
             if($this->position->direction === "E") { 
              return $orders[3]; 
            } 
            if($this->position->direction === "W") { 
              if($key[1][2] === ".") { 
                return $orders[0]; 
              } 
              else{ return $orders[1]; 
              } } 
              else{ return $orders[2]; 
              } }







            }else{

              if((($this->ennemyDir == "N") or ($this->ennemyDir == "S")) and 
                (($this->position->direction == "W") or ($this->position->direction == "E")) or 
                (($this->ennemyDir == "W") or ($this->ennemyDir == "E")) and 
                (($this->position->direction == "N") or ($this->position->direction == "S")))
              {
                $this->ennemyDir = "nada";
                return RobotOrder::AHEAD;
              }elseif($this->ennemyDir == $this->position->direction){
                $this->ennemyDir = "nada";
                return RobotOrder::FIRE;
              }
              else{
                if(($this->prefire == 1) or ($this->prefire == 2) or ($this->prefire == 3) or ($this->prefire == 4)){
                  if($this->prefire == 1){
                    $this->prefire = 2;
                    return RobotOrder::FIRE;
                  }elseif($this->prefire == 2){
                    $this->prefire = 3;
                    return RobotOrder::TURN_RIGHT;
                  }elseif($this->prefire == 3){
                    $this->prefire = 4;
                    return RobotOrder::FIRE;
                  }elseif($this->prefire == 4){
                    $this->prefire = 0;
                    return RobotOrder::TURN_LEFT;
                  }
                }
                else{
                  if (($this->position->y == 10 && $this->position->direction != "W" && $this->position->x != 2)
                    or ($this->position->x == 2 && $this->position->direction != "N" && $this->position->y != 2)
                    or ($this->position->y == 2 && $this->position->direction != "E" && $this->position->x != 11)
                    or ($this->position->x == 11 && $this->position->direction != "S" && $this->position->y != 10))
                  {
                    $this->prefire++;
                    return RobotOrder::TURN_RIGHT;
                  }else{$this->prefire = 0;

                    return RobotOrder::AHEAD;} 
                  }
                }


              }
            }
          }
        }
    }