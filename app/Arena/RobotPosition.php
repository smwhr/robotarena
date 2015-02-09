<?php
namespace Arena;

class RobotPosition{
  public $x;
  public $y;
  public $direction;

  private $_old_x;
  private $_old_y;

  private $new_x;
  private $new_y;

  public function __construct($x, $y, $direction){
    $this->x = $x;
    $this->y = $y;
    $this->direction = $direction;
  }

  public function ahead(){
    $this->_old_x = $this->x;
    $this->_old_y = $this->y;
    $this->new_x  = $this->x;
    $this->new_y  = $this->y;

    switch($this->direction){
      case "N":
        $this->new_y = $this->y - 1;
        break;
      case "S":
        $this->new_y = $this->y + 1;
        break;
      case "E":
        $this->new_x = $this->x + 1;
        break;
      case "W":
        $this->new_x = $this->x - 1;
        break;
    }
    return [$this->new_x, $this->new_y];
  }

  public function rotate($sens){
    if($sens == "left"){
      switch($this->direction){
        case "N":
          $this->direction = "W";
        break;
        case "S":
          $this->direction = "E";
          break;
        case "E":
          $this->direction = "N";
          break;
        case "W":
          $this->direction = "S";
          break;
      }
    }else if($sens == "right"){
      switch($this->direction){
        case "N":
          $this->direction = "E";
        break;
        case "S":
          $this->direction = "W";
          break;
        case "E":
          $this->direction = "S";
          break;
        case "W":
          $this->direction = "N";
          break;
      }
    }
  }

  public function commitMove(){
    $this->x = $this->new_x;
    $this->y = $this->new_y;
  }

  public function rollbackMove(){
    $this->x = $this->_old_x;
    $this->y = $this->_old_y;
  }

  public function copy(){
    return new RobotPosition($this->x, $this->y, $this->direction);
  }
}