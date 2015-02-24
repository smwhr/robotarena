<?php
namespace Robot;

use Arena\RobotOrder;

class TourneEnRond implements RobotInterface{


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

  }
  public function decide(){

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

    return $orders;
  }
}