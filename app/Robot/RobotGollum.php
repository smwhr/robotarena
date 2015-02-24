<?php

namespace Robot;

use Arena\RobotOrder;

class RobotGollum implements RobotInterface{

  public $closestWall;
  public $toucheWall;
  public $direction;
  public $hit = false;
  public $turnAfterHit = 0;
  public $ennemyPosition;
  public $data;
  public $turn;

  public function __construct($name){
    $this->name = $name;
    $this->turn = 0;
    $this->closestWall = "";
    $this->direction = "";
  }

  public function notifyPosition(\Arena\RobotPosition $position){
    //distance par rapport à la case accolée au mur
    //pour chaque mur
    $distance['N'] = ($position->y - 2) ;
    $distance['S'] = (12 - ($position->y + 2));
    $distance['E'] = (13 - ($position->x + 2));
    $distance['W'] = ($position->x - 2);
    //determine si le robot touche un mur
    if($position->x ==  2 || $position->x == 11){
      $this->toucheWall = true;
    }elseif($position->y == 2 || $position->y == 10){
      $this->toucheWall = true;
    }else{
      $this->toucheWall = false;
    }
    //application du malus par tour de rotation en fonction de la direction;
    switch ($position->direction) {
      case "N":
        $distance['E'] += 1;
        $distance['W'] += 1;
        $distance['S'] += 2;
        break;
      case "W":
        $distance['N'] += 1;
        $distance['S'] += 1;
        $distance['E'] += 2;
        break;
      case "S":
        $distance['E'] += 1;
        $distance['W'] += 1;
        $distance['N'] += 2;
        break;
      case "E":
        $distance['N'] += 1;
        $distance['S'] += 1;
        $distance['W'] += 2;
        break;
    }
    //choix du mur le plus proche
    $this->closestWall = array_keys($distance, min($distance))[0];
    //retour de la direction
    $this->direction = $position->direction;

  }
  public function notifySurroundings($data){

    $this->data = $data;
    for ($k = 0; $k <= 4; $k++) {
      $data[$k];
      for ($m = 0; $m <= 4; $m++) {
        if ($data[$k][$m] == 'A' || $data[$k][$m] == 'B') {
          if ($k != 2 || $m != 2) {
            $this->ennemyPosition = [$k => $m];
            break 2;
          }
        } else {
          $this->ennemyPosition = NULL;
        }
      }
    }
    //return $ennemyPosition;
  }
  public function notifyEnnemy($direction){
    $this->hit = $direction;
  }
  public function decide(){
    $this ->turn++;
    //Stratégie réaction lors
    $this->notifySurroundings($this->data);
    if(!is_null($this->ennemyPosition)){


      if(@$this->ennemyPosition[0] == 0){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'E'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif(@$this->ennemyPosition[1] == 0){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'E'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }
      }
      elseif($this->ennemyPosition[2] == 0){
        if($this->direction == 'N'){
          RobotOrder::FIRE;
        }
        elseif($this->direction == 'E'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }
      }
      elseif($this->ennemyPosition[3] == 0){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::TURN_RIGHT;
        }
        else{
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[4] == 0){
        if($this->direction == 'N' || $this->direction == 'E'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'S'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[0] == 1){
        if($this->direction == 'N' || $this->direction == 'E'){
          RobotOrder::TURN_LEFT;
        }
        elseif($this->direction == 'W'){
          RobotOrder::AHEAD;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[1] == 1){
        if($this->direction == 'N'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'W' || $this->direction == 'S' ){
          RobotOrder::TURN_RIGHT;
        }
        else{
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[2] == 1){
        if($this->direction == 'N'){
          RobotOrder::FIRE;
        }
        elseif($this->direction == 'W' || $this->direction == 'S'){
          RobotOrder::TURN_RIGHT;
        }
        else {
          RobotOrder::TURN_LEFT;
        }
      }

      elseif($this->ennemyPosition[3] == 1){
        if($this->direction == 'N'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'E' || $this->direction == 'S'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif ($this->ennemyPosition[4] == 1) {
        if($this->direction == 'N' || $this->direction =='E'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'S'){
          RobotOrder::TURN_LEFT;
        }
        else {
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[0] == 2){
        if($this->direction == 'E'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'N' || $this->direction== 'W'){
          RobotOrder::TURN_LEFT;
        }
        else {
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[1] == 2) {
        if($this->direction == 'W'){
          RobotOrder::FIRE;
        }
        elseif($this->direction == 'N' || $this->direction == 'E'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif ($this->ennemyPosition[3] == 2) {
        if($this->direction == 'W' || $this->direction == 'N'){
          RobotOrder::TURN_RIGHT;
        }
        elseif($this->direction == 'E'){
          RobotOrder::FIRE;
        }
        else {
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[4] == 2){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::TURN_RIGHT;
        }
        elseif($this->direction == 'E'){
          RobotOrder::AHEAD;
        }
        else{
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[0] == 3){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[1] == 3){
        if($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif ($this->ennemyPosition[2] == 3) {
        if($this->direction == 'S'){
          RobotOrder::FIRE;
        }
        elseif($this->direction == 'N' || $this->direction == 'W'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[3] == 3){
        if($this->direction == 'S' || $this->direction == 'E'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'W'){
          RobotOrder::TURN_LEFT;
        }
        else {
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[4] == 3){
        if($this->direction == 'E' || $this->direction == 'S'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'N'){
          RobotOrder::TURN_RIGHT;
        }
        else{
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[0] == 4){
        if($this->direction == 'W' || $this->direction == 'N'){
          RobotOrder::TURN_LEFT;
        }
        elseif($this->direction == 'E'){
          RobotOrder::TURN_RIGHT;
        }
        else {
          RobotOrder::AHEAD;
        }

      }
      elseif($this->ennemyPosition[1] == 4){
        if($this->direction == 'W' || $this->direction == 'N'){
          RobotOrder::TURN_LEFT;
        }
        elseif($this->direction == 'S'){
          RobotOrder::AHEAD;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[2] == 4){
        if($this->direction == 'S'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'W'){
          RobotOrder::TURN_LEFT;
        }
        else{
          RobotOrder::TURN_RIGHT;
        }

      }
      elseif($this->ennemyPosition[3] == 4){
        if($this->direction == 'N' || $this->direction == 'E'){
          RobotOrder:: TURN_RIGHT;
        }
        elseif($this->direction == 'S'){
          RobotOrder::AHEAD;
        }
        else{
          RobotOrder::TURN_LEFT;
        }

      }
      elseif($this->ennemyPosition[4] == 4){
        if($this->direction == 'S' || $this->direction =='E'){
          RobotOrder::AHEAD;
        }
        elseif($this->direction == 'N'){
          RobotOrder::TURN_RIGHT;
        }
        else {
          RobotOrder::TURN_LEFT;
        }
      }
    }

    //Stratégie réplique en cas de tir ennemi
    if($this->hit != false){
      switch ($this->hit) {

        case 'N':
          switch ($this->direction) {
            case 'N':
              $this->hit = 'front';
              break;
            case 'S' :
              $this->hit = 'back';
              break;
            case 'W':
              $this->hit = 'right';
              break;
            case 'E':
              $this->hit = 'left';
              break;
          }
          break;

        case 'S':
          switch ($this->direction) {
            case 'N':
              $this->hit = 'back';
              break;
            case 'S' :
              $this->hit = 'front';
              break;
            case 'W':
              $this->hit = 'left';
              break;
            case 'E':
              $this->hit = 'right';
              break;
          }
          break;

        case 'W':
          switch ($this->direction) {
            case 'N':
              $this->hit = 'left';
              break;
            case 'S' :
              $this->hit = 'right';
              break;
            case 'W':
              $this->hit = 'front';
              break;
            case 'E':
              $this->hit = 'back';
              break;
          }
          break;

        case 'E':
          switch ($this->direction) {
            case 'N':
              $this->hit = 'right';
              break;
            case 'S' :
              $this->hit = 'left';
              break;
            case 'W':
              $this->hit = 'back';
              break;
            case 'E':
              $this->hit = 'front';
              break;
          }
          break;
      }



      switch ($this->hit) {

        case 'front':
          switch ($this->turnAfterHit) {
            case 0:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 1 :
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 2:
              $this->hit = false;
              $this->turnAfterHit = 0;
              break;
          }
          break;

        case 'back':
          $this->hit = false;
          break;

        case 'left':
          switch ($this->turnAfterHit) {
            case 0:
              $this->turnAfterHit++;
              return RobotOrder::AHEAD;
              break;
            case 1 :
              $this->turnAfterHit++;
              return RobotOrder::TURN_LEFT;
              break;
            case 2:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 3:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 4:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 5:
              $this->hit = false;
              $this->turnAfterHit = 0;
              break;
          }
          break;

        case 'right':
          switch ($this->turnAfterHit) {
            case 0:
              $this->turnAfterHit++;
              return RobotOrder::AHEAD;
              break;
            case 1:
              $this->turnAfterHit++;
              return RobotOrder::TURN_RIGHT;
              break;
            case 2:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 3:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 4:
              $this->turnAfterHit++;
              return RobotOrder::FIRE;
              break;
            case 5:
              $this->hit = false;
              $this->turnAfterHit = 0;
              break;
          }
          break;
      }
    }

    //Stratégie du mur
    //UNIQUEMENT SI toucheWall EST FAUX
    //direction et objectif sont similaire>
    //var_dump($this->ennemyPosition);
    if($this->toucheWall != true && $this->ennemyPosition == NULL){
      if($this->direction == $this->closestWall){
        return RobotOrder::AHEAD;
      }
      //pour chaque objectif, par rapport à la direction, on determine l'action
      switch($this->closestWall){
        case "N":
          if($this->direction == "W"){
            return RobotOrder::TURN_RIGHT;
          }elseif($this->direction == "E"){
            return RobotOrder::TURN_LEFT;
          }else{
            return RobotOrder::TURN_RIGHT;
          }
          break;

        case "S":
          if($this->direction == "E"){
            return RobotOrder::TURN_RIGHT;
          }elseif($this->direction == "W"){
            return RobotOrder::TURN_LEFT;
          }else{
            return RobotOrder::TURN_RIGHT;
          }
          break;

        case "E":
          if($this->direction == "N"){
            return RobotOrder::TURN_RIGHT;
          }elseif($this->direction == "S"){
            return RobotOrder::TURN_LEFT;
          }else{
            return RobotOrder::TURN_RIGHT;
          }
          break;

        case "W":
          if($this->direction == "S"){
            return RobotOrder::TURN_RIGHT;
          }elseif($this->direction == "N"){
            return RobotOrder::TURN_LEFT;
          }else{
            return RobotOrder::TURN_RIGHT;
          }
          break;
      }
    }else{
      $orders = [RobotOrder::TURN_LEFT,
              RobotOrder::TURN_RIGHT,
               RobotOrder::AHEAD,
               RobotOrder::FIRE];
    shuffle($orders);

    return $orders[0];
    }

    //defaut
    //$orders = [RobotOrder::TURN_LEFT,
    //          RobotOrder::TURN_RIGHT,
    //           RobotOrder::AHEAD,
    //           RobotOrder::FIRE];
    //shuffle($orders);

    //return $orders[0];
  }
}