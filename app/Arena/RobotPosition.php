<?php
namespace Arena;

class RobotPosition{
  public $x;
  public $y;
  public $direction;

  private $_old_x;
  private $_old_y;

  public function __construct($x, $y){
    $this->x = $x;
    $this->y = $y;
  }

  public function ahead(){
    $this->_old_x = $this->x;
    $this->_old_y = $this->y;

    switch($this->direction){
      case "N":
        $this->y--;
        break;
      case "S":
      $this->y++;
        break;
      case "E":
        $this->x++;
        break;
      case "W":
        $this->y--;
        break;
    }
  }

  public function commitMove(){
    //ok
  }

  public function rollbackMove(){
    $this->x = $this->_old_x;
    $this->y = $this->_old_y;
  }
}