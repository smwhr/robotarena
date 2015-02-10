<?php

namespace Robot;

interface RobotInterface{

  public function notifyPosition(\Arena\RobotPosition $position);
  public function notifySurroundings($data);
  public function notifyEnnemy($direction);
  public function decide();
}