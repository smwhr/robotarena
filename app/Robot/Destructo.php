<?php
namespace Robot;

use Arena\RobotOrder;

class Destructo implements RobotInterface{

  public $hit;
  public $lastHit; 

  public function __construct($name){
    $this->name = $name;
  }
 
  public function notifyPosition(\Arena\RobotPosition $position){
    $this->currentDirection = $position->direction;
  }
  public function notifySurroundings($data){
    $this->currentSurroundings = $data;

  }
  public function notifyEnnemy($direction){
    $this->ennemyDirection = $direction;
    if ($this->ennemyDirection != ""){
      $this->hit++;
      $this->lastHit = $this->ennemyDirection;
    }

  }
  public function decide(){

    if ($this->hit !=0){
      if ($this->lastHit != $this->currentDirection){
        $orders = RobotOrder::TURN_LEFT;
      }
      else{
        $orders = RobotOrder::FIRE;
      }
    }
    else {
      if ($this->currentDirection == "N") {
          if ($this->currentSurroundings[1][2] == "."){
            $orders = RobotOrder::AHEAD;
          }
          else{
            $orders = RobotOrder::TURN_LEFT;
          }
      }
      elseif ($this->currentDirection == "S") {
          if ($this->currentSurroundings[3][2] == "."){
            $orders = RobotOrder::AHEAD;
          }
          else {
            $orders = RobotOrder::TURN_LEFT;
          }
      }
      elseif ($this->currentDirection == "W") {
          if ($this->currentSurroundings[2][1] == "."){
            $orders = RobotOrder::AHEAD;
          }
          else {
            $orders = RobotOrder::TURN_LEFT;
          }   
      }
      elseif ($this->currentDirection == "E") {
          if ($this->currentSurroundings[2][3] == "."){
            $orders = RobotOrder::AHEAD;
          }
          else{
            $orders = RobotOrder::TURN_LEFT;
          }    
      }
    }
    return $orders;
    
  }
  
}