<?php
namespace Robot;
use Arena\RobotOrder;

class RobotDalek implements RobotInterface{

    public $x;
    public $y;
    public $direction;
    public $surrounding;
    public $enemy_direction;
    public $flag;
    private $firstPosOnParcours;
    private $return;
    private $life;


    private $strategie;

    public function __construct($name){
        $this->name = $name;
        $this->flag = true;
        $this->enemy_direction = null;
        $this->firstPosOnParcours = ["x" => 0, "y" => 0, "recup" => false, "countBefore" => 0];
        $this->return = ["turn" => 0, "do" => false];
        $this->life = 10;
    }

    public function notifyPosition(\Arena\RobotPosition $position){
        $this->x = $position->x;
        $this->y = $position->y;
        $this->direction = $position->direction;
    }

    public function notifySurroundings($data){
        $this->surrounding = $data;
    }

    public function notifyEnnemy($direction){
        $this->enemy_direction = $direction;
    }

    public function decide()
    {
        $this->strategie = ["rejoindreParcours" => false, "suivreParcours" => false, "shootedByEnemy" => false, "enemyVisible" => false]; // strategie a false
        $orders = [
            "avance" => RobotOrder::AHEAD,
            "droite" => RobotOrder::TURN_RIGHT, // se retourne vers la droite
            "gauche" => RobotOrder::TURN_LEFT, // idem gauche
            "tire" => RobotOrder::FIRE
        ];

        // DEFINITION DE LA STRATEGIE
        // check for enemy visible dans le surrounding
        $count = ["ligne" => 0, "cell" => 0];
        $result = [];
        foreach ($this->surrounding as $ligne) {
            $count["ligne"]++;
            foreach ($ligne as $point => $valeur) {
                $count["cell"]++;
                if (!($count["ligne"] == 3 && $count["cell"] == 3)) {
                    if (($valeur == "B" || $valeur == "A")) {
                        $result = ["x" => $count["cell"], "y" => $count["ligne"]];
                    }
                }
            }
            $count["cell"] = 0;
        }

        if (!empty($result)) { // si ennemi dans le surrounding
            $this->strategie["enemyVisible"] = true;
            if ($this->enemy_direction != null){
                $this->strategie["shootedByEnemy"] = true;
                $this->life--;
            } // si en plus d'être dans le surrounding l'ennemi nous tire dessu
        } else if ($this->enemy_direction != null) { // si ennemi nous tire dessus
            $this->strategie["shootedByEnemy"] = true;
            $this->life--;
        } else if (($this->y == 4 && ($this->x > 3 && $this->x < 10) || $this->y == 8 && ($this->x > 3 && $this->x < 10)) || ($this->x == 4 && ($this->y > 3 && $this->y < 9) || $this->x == 9 && ($this->y > 3 && $this->y < 9))) { // check si le robot est sur le parcours
            $this->strategie["suivreParcours"] = true;
        } else { // si ennemi invisible ne tire pas dessus et que robot pas sur parcours
            $this->strategie["rejoindreParcours"] = true;
        }

        // definition des actions par strategie
        if ($this->strategie["rejoindreParcours"]) {
            if ($this->x == 2 || $this->x == 3) {
                if ($this->y == 2 || $this->y == 3) { // COINS MAP GAUCHE HAUT
                    if ($this->direction == "S" || $this->direction == "E") return $orders["avance"];
                    if ($this->direction == "N") return $orders["droite"];
                    if ($this->direction == "W") return $orders["gauche"];
                } else if ($this->y == 9 || $this->y == 10) { // COINS MAP GAUCHE DROITE
                    if ($this->direction == "N" || $this->direction == "E") return $orders["avance"];
                    if ($this->direction == "S") return $orders["gauche"];
                    if ($this->direction == "W") return $orders["droite"];
                } else { // MAP GAUCHE ENTRE COINS
                    if ($this->direction == "E") return $orders["avance"];
                    if ($this->direction == "N" || $this->direction == "W") return $orders["droite"];
                    if ($this->direction == "S") return $orders["gauche"];
                }
            } else if ($this->x == 10 || $this->x == 11) { // COINS MAP DROITE
                if ($this->y == 2 || $this->y == 3) {
                    if ($this->direction == "S" || $this->direction == "W") return $orders["avance"];
                    if ($this->direction == "N") return $orders["gauche"];
                    if ($this->direction == "E") return $orders["droite"];
                } else if ($this->y == 9 || $this->y == 10) {
                    if ($this->direction == "N" || $this->direction == "W") return $orders["avance"];
                    if ($this->direction == "S") return $orders["droite"];
                    if ($this->direction == "W") return $orders["gauche"];
                } else {
                    if ($this->direction == "W") return $orders["avance"];
                    if ($this->direction == "S" || $this->direction == "E") return $orders["droite"];
                    if ($this->direction == "N") return $orders["gauche"];
                }
            } else if ($this->x > 3 && $this->x < 10) {
                if ($this->y == 2 || $this->y == 3) {
                    if ($this->direction == "N" || $this->direction == "E") return $orders["droite"];
                    if ($this->direction == "S") return $orders["avance"];
                    if ($this->direction == "W") return $orders["gauche"];
                } else if ($this->y == 9 || $this->y == 10) {
                    if ($this->direction == "N") return $orders["avance"];
                    if ($this->direction == "E" || $this->direction == "S") return $orders["gauche"];
                    if ($this->direction == "W") return $orders["droite"];
                } else if ($this->y > 4 && $this->y < 8) {
                    if ($this->y == 5) {
                        if ($this->x < 7) {
                            if ($this->direction == "N" || $this->direction == "W") return $orders["avance"];
                            if ($this->direction == "S") return $orders["droite"];
                            if ($this->direction == "E") return $orders["gauche"];
                        } else if ($this->x > 6) {
                            if ($this->direction == "N" || $this->direction == "E") return $orders["avance"];
                            if ($this->direction == "S") return $orders["gauche"];
                            if ($this->direction == "E") return $orders["droite"];
                        }
                    } else if ($this->y == 6) {
                        if ($this->x < 7) {
                            if ($this->direction == "E") return $orders["gauche"];
                            else return $orders["avance"];
                        } else if ($this->x > 6) {
                            if ($this->direction == "W") return $orders["gauche"];
                            else return $orders["avance"];
                        }
                    } else if ($this->y == 7) {
                        if ($this->x < 7) {
                            if ($this->direction == "S" || $this->direction == "W") return $orders["avance"];
                            if ($this->direction == "N") return $orders["gauche"];
                            if ($this->direction == "E") return $orders["droite"];
                        } else if ($this->x > 6) {
                            if ($this->direction == "S" || $this->direction == "E") return $orders["avance"];
                            if ($this->direction == "N") return $orders["droite"];
                            if ($this->direction == "W") return $orders["gauche"];
                        }
                    }
                }
            }
        }

        if ($this->strategie["suivreParcours"]) {
            if ($this->x == $this->firstPosOnParcours["x"] && $this->y == $this->firstPosOnParcours["y"]) { // gestion demi-tour
                $this->firstPosOnParcours["countBefore"]++;
                if ($this->firstPosOnParcours["countBefore"] == 3) $this->return["do"] = true;
            }

            if ($this->return["do"] && $this->return["turn"] < 2) {
                $this->return["turn"]++;
                return $orders["droite"];
            }

            if ($this->return["turn"] == 2){
                $this->return["do"] = false;
                $this->return["turn"] = 0;
                $this->firstPosOnParcours["recup"] = false;
                $this->firstPosOnParcours["countBefore"] = 0;
            }

            if ($this->firstPosOnParcours["recup"] == false) { // fin gestion demi-tour
                $this->firstPosOnParcours["x"] = $this->x;
                $this->firstPosOnParcours["y"] = $this->y;
                $this->firstPosOnParcours["recup"] = true;
            }

            // COIN HAUT GAUCHE
            if ($this->x == 4 && $this->y == 4) {
                if ($this->direction == "S" || $this->direction == "E") return $orders["avance"];
                else if ($this->direction == "N") return $orders["droite"];
                else if ($this->direction == "W") return $orders["gauche"];
            }

            // COIN HAUT DROIT
            if ($this->x == 9 && $this->y == 4) {
                if ($this->direction == "S" || $this->direction == "W") return $orders["avance"];
                else if ($this->direction == "N") return $orders["gauche"];
                else if ($this->direction == "E") return $orders["droite"];
            }

            // COIN BAS DROIT
            if ($this->x == 9 && $this->y == 8) {
                if ($this->direction == "N" || $this->direction == "W") return $orders["avance"];
                else if ($this->direction == "S") return $orders["droite"];
                else if ($this->direction == "E") return $orders["gauche"];
            }

            // COIN BAS GAUCHE
            if ($this->x == 4 && $this->y == 8) {
                if ($this->direction == "N" || $this->direction == "E") return $orders["avance"];
                else if ($this->direction == "S") return $orders["gauche"];
                else if ($this->direction == "W") return $orders["droite"];
            }

            // LIGNES HAUT ET BAS
            if ($this->x > 4 && $this->x < 9 && ($this->y == 4 || $this->y == 8)) {
                if ($this->direction == "E" || $this->direction == "W") return $orders["avance"];
                else if ($this->direction == "N") {
                    if ($this->x <= 6) return $orders["gauche"];
                    else return $orders["droite"];
                } else if ($this->direction == "S") {
                    if ($this->x <= 6) return $orders["droite"];
                    else return $orders["gauche"];
                }
            }

            // LIGNE GAUCHE
            if ($this->x == 4 && $this->y > 4 && $this->y < 8) {
                if ($this->direction == "S" || $this->direction == "N") return $orders["avance"];
                else if ($this->direction == "E") {
                    if ($this->y < 6) return $orders["droite"];
                    else if ($this->y > 6) return $orders["gauche"];
                    else if ($this->y == 6) {
                        $dir = mt_rand(0, 1);
                        if ($dir) return $orders["gauche"];
                        else return $orders["droite"];
                    }
                } else if ($this->direction == "W") {
                    if ($this->y < 6) return $orders["gauche"];
                    else if ($this->y > 6) return $orders["droite"];
                    else if ($this->y == 6) {
                        $dir = mt_rand(0, 1);
                        if ($dir) return $orders["droite"];
                        else return $orders["gauche"];
                    }
                }
            }

            // LIGNE DROITE
            if ($this->x == 9 && $this->y > 4 && $this->y < 8) {
                if ($this->direction == "S" || $this->direction == "N") {
                    return $orders["avance"];
                } else if ($this->direction == "E") {
                    if ($this->y < 6) return $orders["droite"];
                    else if ($this->y > 6) return $orders["gauche"];
                    else if ($this->y == 6) {
                        $dir = mt_rand(0, 1);
                        if ($dir) return $orders["gauche"];
                        else return $orders["droite"];
                    }
                } else if ($this->direction == "W") {
                    if ($this->y < 6) return $orders["gauche"];
                    else if ($this->y > 6) return $orders["droite"];
                    else if ($this->y == 6) {
                        $dir = mt_rand(0, 1);
                        if ($dir) return $orders["droite"];
                        else return $orders["gauche"];
                    }
                }
            }
        }

        if ($this->strategie["shootedByEnemy"] && !$this->strategie["enemyVisible"]) {
            if ($this->enemy_direction == "N" || $this->enemy_direction == "S") {
                $this->enemy_direction = null;
                if ($this->direction == "S") return $orders["droite"];
                else if ($this->direction == "N") return $orders["gauche"];
                else return $orders["avance"];
            } else if ($this->enemy_direction == "E" || $this->enemy_direction == "W") {
                $this->enemy_direction = null;
                if ($this->direction == "W") return $orders["droite"];
                else if ($this->direction == "E") return $orders["gauche"];
                else return $orders["avance"];
            }
        }

        else if ($this->strategie["enemyVisible"] && $this->strategie["shootedByEnemy"]) {
            if ($this->enemy_direction == "N") {
                $this->enemy_direction = null;
                if ($this->direction == "S") return $orders["droite"];
                if ($this->direction == "E") return $orders["avance"];
                if ($this->direction == "W") return $orders["avance"];
                if ($this->direction == "N" && $this->life == 1) return $orders["tire"];
                else if ($this->direction == "N" && $this->life != 1) return $orders["droite"];
            } else if ($this->enemy_direction == "E") {
                $this->enemy_direction = null;
                if ($this->direction == "N") return $orders["avance"];
                if ($this->direction == "S") return $orders["avance"];
                if ($this->direction == "E" && $this->life == 1) return $orders["tire"];
                else if ($this->direction == "E" && $this->life != 1) return $orders["droite"];
                if ($this->direction == "W") return $orders["gauche"];
            } else if ($this->enemy_direction == "S") {
                $this->enemy_direction = null;
                if ($this->direction == "N") return $orders["droite"];
                if ($this->direction == "S" && $this->life == 1) return $orders["tire"];
                else if ($this->direction == "S" && $this->life != 1) return $orders["gauche"];
                if ($this->direction == "E") return $orders["avance"];
                if ($this->direction == "W") return $orders["avance"];
            } else if ($this->enemy_direction == "W") {
                $this->enemy_direction = null;
                if ($this->direction == "N") return $orders["avance"];
                if ($this->direction == "S") return $orders["avance"];
                if ($this->direction == "E") return $orders["gauche"];
                if ($this->direction == "W" && $this->life == 1) return $orders["tire"];
                else if ($this->direction == "W" && $this->life != 1) return $orders["gauche"];
            }
        }

        if ($this->strategie["enemyVisible"]) {
            if ($result["x"] == 1) {
                if ($result["y"] == 1) {
                    if ($this->direction == "N" || $this->direction == "W") return $orders["avance"];
                    if ($this->direction == "E") return $orders["droite"];
                    if ($this->direction == "S") return $orders["gauche"];
                }
                if ($result["y"] == 5) {
                    if ($this->direction == "S" || $this->direction == "W") return $orders["avance"];
                    if ($this->direction == "E") return $orders["droite"];
                    if ($this->direction == "N") return $orders["gauche"];
                }
            }

            if ($result["x"] == 5) {
                if ($result["y"] == 1) {
                    if ($this->direction == "N" || $this->direction == "E") return $orders["avance"];
                    if ($this->direction == "W") return $orders["gauche"];
                    if ($this->direction == "S") return $orders["droite"];
                }
                if ($result["y"] == 5) {
                    if ($this->direction == "S" || $this->direction == "E") return $orders["avance"];
                    if ($this->direction == "W") return $orders["gauche"];
                    if ($this->direction == "N") return $orders["droite"];
                }
            }

            if ($result["y"] == 2 && $result["x"] == 1) {
                if ($this->direction == "N") return $orders["gauche"];
                if ($this->direction == "E") return $orders["gauche"];
                if ($this->direction == "S") return $orders["droite"];
            }

            if ($result["x"] == 2 && $result["y"] == 1 || $result["x"] == 2 && $result["y"] == 2) {
                if ($this->direction == "E") return $orders["gauche"];
                if ($this->direction == "S") return $orders["droite"];
                if ($this->direction == "N") return $orders["gauche"];
            }

            if ($result["y"] == 2 && $result["x"] == 5) {
                if ($this->direction == "N") return $orders["droite"];
                if ($this->direction == "W") return $orders["droite"];
                if ($this->direction == "S") return $orders["gauche"];
            }

            if ($result["x"] == 4 && $result["y"] == 1 || $result["x"] == 4 && $result["y"] == 2) {
                if ($this->direction == "W") return $orders["droite"];
                if ($this->direction == "S") return $orders["gauche"];
                if ($this->direction == "E") return $orders["gauche"];
            }

            if ($result["y"] == 4 && $result["x"] == 5) {
                if ($this->direction == "N") return $orders["droite"];
                if ($this->direction == "W") return $orders["droite"];
                if ($this->direction == "S") return $orders["gauche"];
            }

            if ($result["x"] == 4 && $result["y"] == 4 || $result["x"] == 4 && $result["y"] == 5) {
                if ($this->direction == "W") return $orders["gauche"];
                if ($this->direction == "N") return $orders["droite"];
                if ($this->direction == "E") return $orders["droite"];
            }

            if ($result["y"] == 4 && $result["x"] == 1) {
                if ($this->direction == "N") return $orders["gauche"];
                if ($this->direction == "E") return $orders["droite"];
                if ($this->direction == "S") return $orders["droite"];
            }

            if ($result["x"] == 2 && $result["y"] == 4 || $result["x"] == 2 && $result["y"] == 5) {
                if ($this->direction == "E") return $orders["droite"];
                if ($this->direction == "N") return $orders["gauche"];
                if ($this->direction == "W") return $orders["gauche"];
            }

            // cas ou il est aligné
            if ($result["x"] == 3) {
                if ($result["y"] < 3) {
                    if ($this->direction == "N") return $orders["tire"];
                    if ($this->direction == "E") return $orders["avance"];
                    if ($this->direction == "S") return $orders["droite"];
                    if ($this->direction == "W") return $orders["avance"];
                }

                if ($result["y"] > 3) {
                    if ($this->direction == "N") return $orders["gauche"];
                    if ($this->direction == "E") return $orders["avance"];
                    if ($this->direction == "S") return $orders["tire"];
                    if ($this->direction == "W") return $orders["avance"];
                }
            }

            if ($result["y"] == 3) {
                if ($result["x"] < 3) {
                    if ($this->direction == "N") return $orders["avance"];
                    if ($this->direction == "E") return $orders["gauche"];
                    if ($this->direction == "S") return $orders["avance"];
                    if ($this->direction == "W") return $orders["tire"];
                }

                if ($result["x"] > 3) {
                    if ($this->direction == "N") return $orders["avance"];
                    if ($this->direction == "E") return $orders["tire"];
                    if ($this->direction == "S") return $orders["avance"];
                    if ($this->direction == "W") return $orders["droite"];
                }
            }
        }
    }
}