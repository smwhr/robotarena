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
    //ok
  }

  public function rollbackMove(){
    $this->x = $this->_old_x;
    $this->y = $this->_old_y;
  }
}