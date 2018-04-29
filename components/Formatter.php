<?php

namespace app\components;

use yii;

class Formatter extends yii\i18n\Formatter {

    public function asIndianCurrency($value) {
        $explrestunits = "";
        $numbers = explode('.', $value);
        if (isset($numbers[0])) {
            $value = $numbers[0];
        } else {
            $value = 0;
        }
        if (isset($numbers[1])) {
            $points = $numbers[1];
        } else {
            $points = '00';
        }
        if (strlen($value) > 3) {
            $lastthree = substr($value, strlen($value) - 3, strlen($value));
            $restunits = substr($value, 0, strlen($value) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $value;
            $thecash = (int) $thecash;
        }
        return $thecash . "." . $points;
    }

}
