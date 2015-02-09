<?php
namespace Robot;

use Arena\RobotOrder;

class DefaultRobot implements RobotInterface{

  public function __construct($name){
    $this->name = $name;
  }

  public function notifyPosition(int $x, int $y){

  }
  public function notifySurroundings($data){

  }
  public function decide(){
    return RobotOrder::FIRE;
  }
}