<?php
namespace Robot;

use Arena\RobotOrder;




class ImpulseBot implements RobotInterface
{

    private $_x;
    private $_y;
    private $_direction;
    private $_view;
    private $_enemyDirection;
    private $_nearWall = false;
    private $_nearEnemy = false;
    private $_wallPosition;
    private $_lastOrder;
    private $_goTop = true;
    private $_enemyNotify;
    private $_patternVerticalShot = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function notifyPosition(\Arena\RobotPosition $position)
    {
        $this->_x = $position->x;
        $this->_y = $position->y;
        $this->_direction = $position->direction;
    }

    public function notifySurroundings($data)
    {
        $data[2][2] = ".";
        $this->_view = $data;
foreach ($data AS $row) {
    foreach ($row AS $column) {
        if ($column != "." && $column != "x" ) {
            $this->_nearEnemy = true;
            break 2;
        } else {
            $this->_nearEnemy = false;

        }
    }
}
    }

    public function notifyEnnemy($direction)
    {
        $this->_enemyDirection = $direction;
        $this->_enemyNotify = true;

    }

    public function decide()
    {


        //Array with orders to return.
        $orders = [
            RobotOrder::TURN_LEFT,
            RobotOrder::TURN_RIGHT,
            RobotOrder::AHEAD,
            RobotOrder::FIRE
        ];


        $line3 = $this->_view[2];



        if ($this->_nearWall == false && $this->_nearEnemy == false && $this->_enemyNotify == false) {
            //  ======================================================================================================
            // ============ Move robot to right if "$_x" > 7 and there no wall at left on he's view range.
            if ($this->_x > 7) {
                if ($line3[3] != "x") {
                    $this->_nearWall = false;
                } else {
                    $this->_nearWall = true;
                    $this->_wallPosition = "right";

                }

                if ($this->_nearWall == false && $this->_direction != "E") {
                    //Turn right
                    return $orders[1];
                } else {
                    if ($this->_nearWall == false && $this->_direction == "E") {
                        //forward
                        return $orders[2];
                    }
                }


            } else {

                //  ========= Move robot to left wall if  "$_x" >= 7 there no wall at left on he's view range.

                if ($line3[1] != "x") {
                    $this->_nearWall = false;
                } else {
                    $this->_nearWall = true;
                    $this->_wallPosition = "left";
                }

                if ($this->_nearWall == false && $this->_direction != "W") {
                    //Turn left
                    return $orders[0];
                } else {
                    if ($this->_nearWall == false && $this->_direction == "W") {
                        //forward
                        return $orders[2];
                    }
                }
            }
            // =========== End Action if robot not near a wall or an enemy ======================
            // ======================================================================================================


        }

        if ($this->_nearWall == true && $this->_nearEnemy == false && $this->_enemyNotify == false) {

            // =========== Backward and forward vertically when near wall ==================

            // for shot vertically if there wall upper or under us

            if ($this->_view[1][2] == "x" && $this->_direction == "S" && $this->_lastOrder != $orders[3] && $this->_patternVerticalShot == false) {
                //$this->_lastOrder = $orders[3];
                $this->_patternVerticalShot = true;
                return $orders[3];
            }
            if ($this->_view[3][2] == "x" && $this->_direction == "N" && $this->_lastOrder != $orders[3] && $this->_patternVerticalShot == false) {
                //$this->_lastOrder = $orders[3];
                $this->_patternVerticalShot = true;
                return $orders[3];
            }


            if ($this->_wallPosition == "left") {
                // ========== for wall on left and go up.
                $this->_patternVerticalShot = false;

                if ($this->_view[1][2] != "x" && $this->_goTop == true) {
                    $this->_goTop = true;
                    if ($this->_direction == "W") {
                        $this->_lastOrder = $orders[1];
                        return $orders[1];

                    } else {
                        if ($this->_direction == "N" && $this->_lastOrder == $orders[1]) {
                            $this->_lastOrder = $orders[2];
                            return $orders[3];
                        } else {
                            switch ($this->_lastOrder) {

                                //if last orders is forward, do turn right
                                case $orders[2]:
                                    $this->_lastOrder = $orders[1];
                                    return $orders[1];
                                    break;

                                //if last orders is turn right, do fire
                                case $orders[1]:
                                    $this->_lastOrder = $orders[3];
                                    return $orders[3];
                                    break;

                                //if last orders is fire, do turn left
                                case $orders[3]:
                                    $this->_lastOrder = $orders[0];
                                    return $orders[0];
                                    break;

                                //if last orders is turn left, do forward
                                case $orders[0]:
                                    $this->_lastOrder = $orders[2];
                                    return $orders[2];
                                    break;

                                //if default do random
                                default:
                                    shuffle($orders);
                                    return $orders[0];
                                    break;
                            }


                        }
                    }
                } else {
                    if ($this->_view[3][2] != "x") {
                        $this->_goTop = false;

                        //for go down
                        if ($this->_direction == "W") {
                            $this->_lastOrder = $orders[0];
                            return $orders[0];
                            //rotation when first move after change up to down
                        } else {
                            if ($this->_direction == "N") {
                                $this->_lastOrder = $orders[0];
                                return $orders[1];
                            } else {
                                if ($this->_direction == "S" && $this->_lastOrder == $orders[0]) {
                                    $this->_lastOrder = $orders[2];
                                    return $orders[3];
                                } else {
                                    switch ($this->_lastOrder) {

                                        //if last orders is fire, do turn right
                                        case $orders[3]:
                                            $this->_lastOrder = $orders[1];
                                            return $orders[1];
                                            break;

                                        //if last orders is turn left, do fire
                                        case $orders[0]:
                                            $this->_lastOrder = $orders[3];
                                            return $orders[3];
                                            break;

                                        //if last orders is forward, do turn left
                                        case $orders[2]:
                                            $this->_lastOrder = $orders[0];
                                            return $orders[0];
                                            break;

                                        //if last orders is turn right, do forward
                                        case $orders[1]:
                                            $this->_lastOrder = $orders[2];
                                            return $orders[2];
                                            break;

                                        //if default do random
                                        default:
                                            shuffle($orders);
                                            return $orders[0];
                                            break;
                                    }


                                }
                            }
                        }
                    } else {
                        $this->_goTop = true;
                        $this->_lastOrder = $orders[1];
                        return $orders[0];
                    }
                }

            }


            if ($this->_wallPosition == "right") {
                // ========== for wall on right. =================
                $this->_patternVerticalShot = false;

                if ($this->_view[1][2] != "x" && $this->_goTop == true) {
                    $this->_goTop = true;
                    //for go top
                    if ($this->_direction == "E") {
                        $this->_lastOrder = $orders[0];
                        return $orders[0];

                    } else {
                        if ($this->_direction == "N" && $this->_lastOrder == $orders[0]) {
                            $this->_lastOrder = $orders[2];
                            return $orders[3];

                        } else {
                            switch ($this->_lastOrder) {

                                //if last orders is fire, do turn right
                                case $orders[3]:
                                    $this->_lastOrder = $orders[1];
                                    return $orders[1];
                                    break;

                                //if last orders is turn left, do fire
                                case $orders[0]:
                                    $this->_lastOrder = $orders[3];
                                    return $orders[3];
                                    break;

                                //if last orders is forward, do turn left
                                case $orders[2]:
                                    $this->_lastOrder = $orders[0];
                                    return $orders[0];
                                    break;

                                //if last orders is turn right, do forward
                                case $orders[1]:
                                    $this->_lastOrder = $orders[2];
                                    return $orders[2];
                                    break;

                                //if default do random
                                default:
                                    shuffle($orders);
                                    return $orders[0];
                                    break;
                            }


                        }
                    }

                } else {
                    if ($this->_view[3][2] != "x") {
                        $this->_goTop = false;

                        //for go down
                        if ($this->_direction == "E") {
                            $this->_lastOrder = $orders[1];
                            return $orders[1];

                        } else {
                            if ($this->_direction == "S" && $this->_lastOrder == $orders[1]) {
                                $this->_lastOrder = $orders[2];
                                return $orders[3];
                                //rotation when first move after change up to down
                            } else {
                                if ($this->_direction == "N") {
                                    $this->_lastOrder = $orders[1];
                                    return $orders[0];
                                } else {
                                    switch ($this->_lastOrder) {

                                        //if last orders is forward, do turn right
                                        case $orders[2]:
                                            $this->_lastOrder = $orders[1];
                                            return $orders[1];
                                            break;

                                        //if last orders is turn right, do fire
                                        case $orders[1]:
                                            $this->_lastOrder = $orders[3];
                                            return $orders[3];
                                            break;

                                        //if last orders is fire, do turn left
                                        case $orders[3]:
                                            $this->_lastOrder = $orders[0];
                                            return $orders[0];

                                            break;

                                        //if last orders is turn left, do forward
                                        case $orders[0]:
                                            $this->_lastOrder = $orders[2];
                                            return $orders[2];
                                            break;

                                        //if default do random
                                        default:
                                            shuffle($orders);
                                            return $orders[0];
                                            break;
                                    }


                                }
                            }
                        }
                    } else {
                        $this->_goTop = true;
                        $this->_lastOrder = $orders[0];
                        return $orders[1];
                    }
                }
            }
        }
        //============ End of This part ======================================================

        if ($this->_nearEnemy == true) {
            // ============== if we spot enemy on our fire line. ==============================
            $this->_nearWall = false;
            $this->_patternVerticalShot = false;
            //if spot on line up above us.
            if (($this->_view[1][2] != "x" && $this->_view[1][2] != ".") || ($this->_view[0][2] != "x" && $this->_view[0][2] != ".")
                ||($this->_view[1][1] != "x" && $this->_view[1][1] != ".") || ($this->_view[1][3] != "x" && $this->_view[1][3] != ".")
                ||($this->_view[0][1] != "x" && $this->_view[0][1] != ".") || ($this->_view[0][3] != "x" && $this->_view[0][3] != "."  )) {
                switch ($this->_direction) {
                    case "E":
                        return $orders[0];
                        break;
                    case "W":
                        return $orders[1];
                        break;
                    case "S":
                        return $orders[1];
                        break;
                    case "N":
                        return $orders[3];
                        break;
                }
                //if spot on line down above us.
            } else if (($this->_view[4][2] != "x" && $this->_view[4][2] != ".") || ($this->_view[3][2] != "x" && $this->_view[3][2] != ".")
                ||($this->_view[4][1] != "x" && $this->_view[4][1] != ".") || ($this->_view[3][3] != "x" && $this->_view[3][3] != ".")
                ||($this->_view[3][1] != "x" && $this->_view[3][1] != ".") || ($this->_view[4][3] != "x" && $this->_view[4][3] != "."  )) {
                    switch ($this->_direction) {
                        case "E":
                            return $orders[1];
                            break;
                        case "W":
                            return $orders[0];
                            break;
                        case "S":
                            return $orders[3];
                            break;
                        case "N":
                            return $orders[1];
                            break;
                    }
                //if spot on left of us
                } else if (($this->_view[2][0] != "x" && $this->_view[2][0] != ".") || ($this->_view[2][1] != "x" && $this->_view[2][1] != ".")
                ||($this->_view[1][1] != "x" && $this->_view[1][1] != ".") || ($this->_view[1][0] != "x" && $this->_view[1][0] != ".")
                ||($this->_view[3][1] != "x" && $this->_view[3][1] != ".") || ($this->_view[3][0] != "x" && $this->_view[3][0] != "."  )) {
                switch ($this->_direction) {
                    case "E":
                        return $orders[1];
                        break;
                    case "W":
                        return $orders[3];
                        break;
                    case "S":
                        return $orders[1];
                        break;
                    case "N":
                        return $orders[0];
                        break;
                }

            } else if (($this->_view[2][3] != "x" && $this->_view[2][3] != ".") || ($this->_view[2][4] != "x" && $this->_view[2][4] != ".")
                ||($this->_view[1][4] != "x" && $this->_view[1][4] != ".") || ($this->_view[1][3] != "x" && $this->_view[1][3] != ".")
                ||($this->_view[3][3] != "x" && $this->_view[3][3] != ".") || ($this->_view[3][4] != "x" && $this->_view[3][4] != "."  )) {
                switch ($this->_direction) {
                    case "E":
                        return $orders[3];
                        break;
                    case "W":
                        return $orders[1];
                        break;
                    case "S":
                        return $orders[0];
                        break;
                    case "N":
                        return $orders[1];
                        break;
                }

            } else {
                $this->_nearEnemy = false;
                shuffle($orders);
                return $orders[0];

            }

            // ========================== Second Move, If ennemy is spot not in our fire line ===============================================






        }
        if ($this->_enemyNotify == true && $this->_nearEnemy == false) {
            $this->_nearWall = false;
            $this->_patternVerticalShot = false;
            if ($this->_enemyDirection == $this->_direction) {
                $this->_enemyNotify = false;
                return $orders[3];
            } else {
                if ($this->_enemyDirection == "N") {
                    switch ($this->_direction) {
                        case "E":

                            return $orders[0];
                            break;
                        case "W":
                            return $orders[1];
                            break;
                        case "S":
                            return $orders[1];
                            break;
                    }
                } else {
                    if ($this->_enemyDirection == "E") {
                        switch ($this->_direction) {
                            case "W":
                                return $orders[0];
                                break;
                            case "N":
                                return $orders[1];
                                break;
                            case "S":
                                return $orders[0];
                                break;

                        }
                    } else {
                        if ($this->_enemyDirection == "W") {
                            switch ($this->_direction) {
                                case "E":
                                    return $orders[0];
                                    break;
                                case "N":
                                    return $orders[0];
                                    break;
                                case "S":
                                    return $orders[1];
                                    break;
                            }
                        } else {
                            switch ($this->_direction) {
                                case "E":
                                    return $orders[1];
                                    break;
                                case "N":
                                    return $orders[0];
                                    break;
                                case "W":
                                    return $orders[0];
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
}