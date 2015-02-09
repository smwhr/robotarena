<?php

namespace Robot;

interface RobotInterface{

  public function notifyPosition(int $x, int $y);
  public function notifySurroundings($data);
  public function decide();
}