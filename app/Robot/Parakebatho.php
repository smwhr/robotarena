<?php

namespace Robot;

use Arena\RobotOrder;

class Parakebatho implements RobotInterface{
    private $table = [];
    private $move = 'left';
    private $position;
    private $debut = true;
    public function __construct($name){
        $this->name = $name;
    }

    public function notifyPosition(\Arena\RobotPosition $position){
        $this->position = $position->direction;
    }
    public function notifySurroundings($data){
        $this->table = $data;
    }
    public function notifyEnnemy($direction){

    }
    public function decide(){
        if ($this->table[0][2] == 'A' || $this->table[0][2] == 'B' || $this->table[1][2] == 'A' || $this->table[1][2] == 'B' || $this->table[2][0] == 'A' || $this->table[2][0] == 'B' || $this->table[2][1] == 'A' || $this->table[2][1] == 'B' || $this->table[3][2] == 'A' || $this->table[3][2] == 'B' || $this->table[4][2] == 'A' || $this->table[4][2] == 'B' || $this->table[2][3] == 'A' || $this->table[2][3] == 'B' || $this->table[2][4] == 'A' || $this->table[2][4] == 'B') {
            if ($this->table[0][2] == 'A' || $this->table[0][2] == 'B' || $this->table[1][2] == 'A' || $this->table[1][2] == 'B') {
                if ($this->position == 'N') {
                    $orders = RobotOrder::FIRE;
                } else {
                    $orders = RobotOrder::TURN_LEFT;
                }
            }
            if ($this->table[2][0] == 'A' || $this->table[2][0] == 'B' || $this->table[2][1] == 'A' || $this->table[2][1] == 'B') {
                if ($this->position == 'W') {
                    $orders = RobotOrder::FIRE;
                } else {
                    $orders = RobotOrder::TURN_LEFT;
                }
            }
            if ($this->table[2][3] == 'A' || $this->table[2][3] == 'B' || $this->table[2][4] == 'A' || $this->table[2][4] == 'B') {
                if ($this->position == 'E') {
                    $orders = RobotOrder::FIRE;
                } else {
                    $orders = RobotOrder::TURN_LEFT;
                }
            }
            if ($this->table[3][2] == 'A' || $this->table[3][2] == 'B' || $this->table[4][2] == 'A' || $this->table[4][2] == 'B') {
                if ($this->position == 'S') {
                    $orders = RobotOrder::FIRE;
                } else {
                    $orders = RobotOrder::TURN_LEFT;
                }
            }
        } else {
            switch ($this->position) {
                case 'N':
                    if ($this->table[0][2] != 'x') {
                        $orders = RobotOrder::AHEAD;
                    } else {
                        $orders = RobotOrder::TURN_LEFT;
                    }
                    break;
                case 'W':
                    if ($this->table[2][4] != 'x') {
                        $orders = RobotOrder::AHEAD;
                    } else {
                        $orders = RobotOrder::TURN_LEFT;
                    }
                    break;
                case 'E':
                    if ($this->table[2][0] != 'x') {
                        $orders = RobotOrder::AHEAD;
                    } else {
                        $orders = RobotOrder::TURN_LEFT;
                    }
                    break;
                case 'S':
                    if ($this->table[4][2] != 'x') {
                        $orders = RobotOrder::AHEAD;
                    } else {
                        $orders = RobotOrder::TURN_LEFT;
                    }
                    break;
            }
        }
        return $orders;
    }
}