<?php
namespace Robot;

use Arena\RobotOrder;

class MadShooter implements RobotInterface{
  public $turn;

  public function __construct($name){
    $this->name = $name;
    $this->turn = 0;
  }

  public function notifyPosition(\Arena\RobotPosition $position){

  }
  public function notifySurroundings($data){

  }
  public function notifyEnnemy($direction){

  }
  public function decide(){
    $this->turn++;
    if($this->turn%2 == 0){
      return RobotOrder::FIRE;
    }else{
      return RobotOrder::TURN_RIGHT;
    }
    
  }
}