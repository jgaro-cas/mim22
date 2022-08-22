<?php

namespace App\CustomServices;

use Carbon\Carbon;

class DateFormater {

    public function toFrenchReadable(Carbon $date) : String{

        $readable = substr(ucfirst($date->locale('fr')->dayName), 0, 3) . ' ';

        if (strlen(strval($date->day)) == 2){
            $readable = $readable . strval($date->day) . ' ';
        } else {
            $readable = $readable . '0' . strval($date->day) . ' ';
        }

        $readable = $readable . $date->locale('fr')->monthName . ' ';

        // Ne pas afficher l'année
        // if (strlen(strval($date->year)) == 2) {
        //     $readable = $readable . strval($date->year) . ' ';
        // } else {
        //     $readable = $readable . '0' . strval($date->year) . ' ';
        // }

        if (strlen(strval($date->hour)) == 2){
            $readable = $readable . strval($date->hour) . ':';
        } else {
            $readable = $readable . '0' . strval($date->hour) . ':';
        }

        if (strlen(strval($date->minute)) == 2){
            $readable = $readable . strval($date->minute);
        } else {
            $readable = $readable . '0' . strval($date->minute);
        }

        return $readable;


    }

}
