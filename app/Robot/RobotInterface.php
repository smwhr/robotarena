<?php

namespace Robot;

interface RobotInterface{

  public function notifyPosition($x, $y, $direction);
  public function notifySurroundings($data);
  public function notifyEnnemy($direction);
  public function decide();
}