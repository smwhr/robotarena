<?php
namespace Robot;

use Arena\RobotOrder;

class DefaultRobot implements RobotInterface{

  public function __construct($name){
    $this->name = $name;
  }

  public function notifyPosition($x,$y){

  }
  public function notifySurroundings($data){

  }
  public function notifyEnnemy($direction){

  }
  public function decide(){
    $orders = [RobotOrder::TURN_LEFT,
               RobotOrder::TURN_RIGHT,
               RobotOrder::AHEAD,
               RobotOrder::FIRE];
    shuffle($orders);
    return $orders[0];
  }
}