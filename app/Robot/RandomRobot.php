<?php
namespace Robot;


use Arena\RobotOrder;
class RandomRobot implements RobotInterface{
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
    $orders = [RobotOrder::TURN_RIGHT,
               RobotOrder::TURN_LEFT,
               RobotOrder::AHEAD];
    if($this->turn%2 == 0){
      return RobotOrder::FIRE;
    }else{
      shuffle($orders);
      return $orders[0];
    }
    
  }
}