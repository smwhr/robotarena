<?php
namespace Robot;

use Arena\RobotOrder;

class BafisteLaRebelle implements RobotInterface{

    public $x;
    public $y;
    public $direction;
    public $surrounding;
    public $enemy_direction;
    public $enemy_position;
    public $i;
    public $flag;
    public $chemin_a_suivre = [
        0 => ["x" => 4, "y" => 4], // LIGNE HORIZONTALE NORD COIN EST
        1 => ["x" => 5, "y" => 4],
        2 => ["x" => 6, "y" => 4],
        3 => ["x" => 7, "y" => 4],
        4 => ["x" => 8, "y" => 4],
        5 => ["x" => 9, "y" => 4], // LIGNE HORIZONTALE NORD COIN WEST
        6 => ["x" => 9, "y" => 5],
        7 => ["x" => 9, "y" => 6], // LIGNE VERTICAL EST
        8 => ["x" => 9, "y" => 7],
        9 => ["x" => 9, "y" => 8], // LIGNE HORIZONTALE SUD COIN WEST
        10 => ["x" => 8, "y" => 8],
        11 => ["x" => 7, "y" => 8],
        12 => ["x" => 6, "y" => 8],
        13 => ["x" => 5, "y" => 8],
        14 => ["x" => 4, "y" => 8], // LIGNE HORIZONTALE SUD COIN EST
        15 => ["x" => 4, "y" => 7],
        16 => ["x" => 4, "y" => 6], // LIGNE VERTICAL WEST
        17 => ["x" => 4, "y" => 5],
    ];
    private $strategie_depart;
    public function __construct($name){
        $this->name = $name;
        $this->i = 1;
        $this->flag = true;
        $this->strategie_depart = true;
        $this->strategie_ennemi_visible = false;
        $this->strategie_step1 = false;
        $this->strategie_step2 = false;
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
    public function decide(){
        $position_actuelle = ["x" => $this->x, "y" => $this->y];
        $orders = ["avance" => RobotOrder::AHEAD,
            "gauche" => RobotOrder::TURN_LEFT,
            "droite" => RobotOrder::TURN_RIGHT,
            "tire" => RobotOrder::FIRE];
        $direction = ["nord" => "N",  //On s'en sert pour le notifyEnnemy
            "est" => "E",
            "ouest" => "W",
            "sud" => "S"];
        foreach($this->surrounding as $y => $array){
            foreach($array as $x => $value){
                if($value != '.' && $value != 'x' && ($x !=2 || $y!=2 )){
                    $this->enemy_position = [
                        "x" => $x,
                        "y" => $y
                    ];
                    $this->strategie_depart = false;
                    $this->strategie_ennemi_visible = true;
                }
            }
        }
        if(!$this->strategie_step2){
            foreach($direction as $key){
                if($this->enemy_direction == $key){
                    $this->strategie_depart = false;
                    $this->strategie_ennemi_visible = false;
                    $this->strategie_step1 = true;
                }
            }}
        //return $orders["gauche"];
        if ($this->strategie_depart) {
            if (in_array($position_actuelle,$this->chemin_a_suivre )) {
                // suivre le parcour
                // chercher position dans array des coord et commencer le parcour
                if($this->y == 4 ){  // Si il est sur la ligne horizontale superieur du parcour
                    if($this->x == 4 || $this->x == 9){ //Si il est dans un coin du parcour
                        if($this->x == 4){ // COINS GAUCHE
                            if($this->direction == "W"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "N"){
                                return $orders["droite"];
                            }
                            else{        //COINS HDW
                                return $orders["avance"];
                            }
                        }
                        else{ // COINS DROIT
                            if($this->direction == "N"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "E"){
                                return $orders["droite"];
                            }
                            else{        //COINS HDW
                                return $orders["avance"];
                            }
                        }
                    }
                    else{ // Si il n'est pas dans un des coins
                        if($this->direction == "N" || $this->direction == "S"){
                            return $orders["gauche"];
                        }
                        else{
                            return $orders["avance"];
                        }
                    }
                }
                if($this->y == 8 ){  // Si il est sur la ligne horizontale inferieur du parcour
                    if($this->x == 4 || $this->x == 9){ //Si il est dans un coin du parcour
                        if($this->x == 4){ // COINS GAUCHE
                            if($this->direction == "S"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "W"){
                                return $orders["droite"];
                            }
                            else{        //COINS HDW
                                return $orders["avance"];
                            }
                        }
                        else{ // COINS DROIT
                            if($this->direction == "E"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "S"){
                                return $orders["droite"];
                            }
                            else{        //COINS HDW
                                return $orders["avance"];
                            }
                        }
                    }
                    else{ // Si il n'est pas dans un des coins
                        if($this->direction == "N" || $this->direction == "S"){
                            return $orders["gauche"];
                        }
                        else{
                            return $orders["avance"];
                        }
                    }
                }
                if(($this->x == 4 || $this->x == 9) && ($this->y != 4 && $this->y != 8)){  // SI IL EST SUR LES LIGNE VERTICALES
                    if($this->direction == "E" || $this->direction == "W"){
                        return $orders["gauche"];
                    }
                    else{
                        return $orders["avance"];
                    }
                }
            }
            else {
                if($this->y == 2 || $this->y == 3){ //si on est au dessus du parcour
                    if($this->direction == "N" || $this->direction == "W"){
                        return $orders["gauche"];
                    }
                    elseif($this->direction == "E"){
                        return $orders["droite"];
                    }
                    else{
                        return $orders["avance"];
                    }
                }
                elseif($this->y == 9 || $this->y == 10){  //Si on en dessous du parcour
                    if($this->direction == "S" || $this->direction == "E"){
                        return $orders["gauche"];
                    }
                    elseif($this->direction == "W"){
                        return $orders["droite"];
                    }
                    else{
                        return $orders["avance"];
                    }
                }
                elseif($this->x == 2 || $this->x == 3){ // Si on est a gauche dans le parcour
                    if($this->direction == "S"){
                        return $orders["gauche"];
                    }
                    elseif($this->direction == "W" || $this->direction == "N"){
                        return $orders["droite"];
                    }
                    else{
                        return $orders["avance"];
                    }
                }
                elseif($this->x == 10 || $this->x == 11){
                    if($this->direction == "S"){
                        return $orders["droite"];
                    }
                    elseif($this->direction == "N" || $this->direction == "E"){
                        return $orders["gauche"];
                    }
                    else{
                        return $orders["avance"];
                    }
                }
                else{  // Dans l'interieur du parcour
                    if($this->y == 7) {  // Coins gauche
                        if($this->x == 5) {  //Sud
                            if ($this->direction == "N") {
                                return $orders["gauche"];
                            } elseif ($this->direction == "E") {
                                return $orders["droite"];
                            } else {
                                return $orders["avance"];
                            }
                        }
                        elseif($this->x == 8) {
                            if ($this->direction == "W") {
                                return $orders["gauche"];
                            } elseif ($this->direction == "N") {
                                return $orders["droite"];
                            } else {
                                return $orders["avance"];
                            }
                        }
                        else{
                            if($this->direction == "W"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "E"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }}
                    }
                    elseif($this->y == 5){
                        if($this->x == 5 ){ //Nord
                            if($this->direction == "E" ){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "S"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }
                        }
                        elseif($this->x == 8 ){
                            if($this->direction == "S"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "W"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }
                        }
                        else{
                            if($this->direction == "E"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "W"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }
                        }
                    }
                    elseif($this->y == 6){
                        if ($this->x==6 || $this->x==5) {
                            if($this->direction == "N"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "E" || $this->direction == "S"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }
                        }
                        elseif($this->x==7 || $this->x==8){
                            if($this->direction == "S"){
                                return $orders["gauche"];
                            }
                            elseif($this->direction == "W" || $this->direction == "N"){
                                return $orders["droite"];
                            }
                            else{
                                return $orders["avance"];
                            }
                        }
                    }
                }
            } // déterminer le chemin le plus court pour atteindre le parcour
        }
        else if ($this->strategie_ennemi_visible) {
            if($this->enemy_position["y"] > 2){  //Si l'ennemie est en dessous
                if($this->enemy_position["x"] < 2){  //Sur la gauche
                    if($this->direction == "S"){
                        return $orders["droite"];
                    }elseif($this->direction == "N" || $this->direction == "E"){
                        return $orders["gauche"];
                    }else{
                        return $orders["avance"];
                    }
                }elseif($this->enemy_position["x"] > 2){ //Sur la droite
                    if($this->direction == "S"){
                        return $orders["gauche"];
                    }elseif($this->direction == "N" || $this->direction == "W"){
                        return $orders["droite"];
                    }else{
                        return $orders["avance"];
                    }
                }else{
                    if($this->direction == "W"){  // Il se positionne au dessus et lui explose sa mere
                        return $orders["gauche"];
                    }elseif($this->direction == "N" || $this->direction == "E"){
                        return $orders["droite"];
                    }else{
                        return $orders["tire"];
                    }
                }
            }elseif($this->enemy_position["y"] < 2){ // Si l'ennemie est au dessus
                if($this->enemy_position["x"] < 2){  //Sur la gauche
                    if($this->direction == "S"){
                        return $orders["droite"];
                    }elseif($this->direction == "N" || $this->direction == "E"){
                        return $orders["gauche"];
                    }else{
                        return $orders["avance"];
                    }
                }elseif($this->enemy_position["x"] > 2){ //Sur la droite
                    if($this->direction == "S"){
                        return $orders["gauche"];
                    }elseif($this->direction == "N" || $this->direction == "W"){
                        return $orders["droite"];
                    }else{
                        return $orders["avance"];
                    }
                }else{
                    if($this->direction == "W"){  // Il se positionne au dessous et lui explose sa mere
                        return $orders["droite"];
                    }elseif($this->direction == "S" || $this->direction == "E"){
                        return $orders["gauche"];
                    }else{
                        return $orders["tire"];
                    }
                }
            }
            else{  //Si l'ennemie est sur la même ligne
                if($this->enemy_position["x"] < 2){
                    if($this->direction == "S"){
                        return $orders["droite"];
                    }elseif($this->direction == "N" || $this->direction == "E"){
                        return $orders["gauche"];
                    }else{
                        return $orders["tire"];
                    }
                }elseif($this->enemy_position["x"] > 2){ //Sur la droite
                    if($this->direction == "S"){
                        return $orders["gauche"];
                    }elseif($this->direction == "N" || $this->direction == "W"){
                        return $orders["droite"];
                    }else{
                        return $orders["tire"];
                    }
                }else{
                }
            }
        }
        else{
            if($this->strategie_step1){
                $this->strategie_step1 = false;
                if($this->enemy_direction == "E"){
                    if($this->direction == "E"){
                        $this->enemy_direction = "";
                        $this->strategie_depart = true;
                        return $orders["tire"];
                    }elseif($this->direction == "N" || $this->direction == "S"){
                        $this->strategie_step2 = true;
                        $this->enemy_direction = "";
                        return $orders["avance"];
                    }else{
                        $this->strategie_step1 = true;
                        return $orders["gauche"];
                    }
                }
                elseif($this->enemy_direction == "S"){
                    if($this->direction == "S"){
                        $this->enemy_direction = "";
                        $this->strategie_depart = true;
                        return $orders["tire"];
                    }elseif($this->direction == "E" || $this->direction == "W"){
                        $this->strategie_step2 = true;
                        $this->enemy_direction = "";
                        return $orders["avance"];
                    }else{
                        $this->strategie_step1 = true;
                        return $orders["gauche"];
                    }
                }
                elseif($this->enemy_direction == "N"){
                    if($this->direction == "N"){
                        $this->enemy_direction = "";
                        $this->strategie_depart = true;
                        return $orders["tire"];
                    }elseif($this->direction == "E" || $this->direction == "W"){
                        $this->strategie_step2 = true;
                        $this->enemy_direction = "";
                        return $orders["avance"];
                    }else{
                        $this->strategie_step1 = true;
                        return $orders["gauche"];
                    }
                }
                else{
                    if($this->direction == "W"){
                        $this->enemy_direction = "";
                        $this->strategie_depart = true;
                        return $orders["tire"];
                    }elseif($this->direction == "N" || $this->direction == "S"){
                        $this->strategie_step2 = true;
                        $this->enemy_direction = "";
                        return $orders["avance"];
                    }else{
                        $this->strategie_step1 = true;
                        return $orders["gauche"];
                    }
                }
            }
            elseif($this->strategie_step2){
                if($this->enemy_direction == "E"){
                    if($this->direction == "N"){
                        $this->strategie_depart = true;
                        return $orders["droite"];
                    }
                    else{
                        $this->strategie_depart = true;
                        return $orders["gauche"];
                    }
                }
                elseif($this->enemy_direction == "W"){
                    if($this->direction == "N"){
                        $this->strategie_depart = true;
                        return $orders["gauche"];
                    }
                    else{
                        $this->strategie_depart = true;
                        return $orders["droite"];
                    }
                }
                elseif($this->enemy_direction == "N"){
                    if($this->direction == "E"){
                        $this->strategie_depart = true;
                        return $orders["gauche"];
                    }
                    else{
                        $this->strategie_depart = true;
                        return $orders["droite"];
                    }
                }
                elseif($this->enemy_direction == "S"){
                    if($this->direction == "E"){
                        $this->strategie_depart = true;
                        return $orders["droite"];
                    }
                    else{
                        $this->strategie_depart = true;
                        return $orders["gauche"];
                    }
                }
            }
        }
    }
}