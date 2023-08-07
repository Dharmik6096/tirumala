<?php

namespace app\components;

use yii\base\Component;
use Yii;

class DayHelper extends Component {

    const MONDAY = 'Mon';
    const TUESDAY = 'Tue';
    const WEDENSDAY = 'Wed';
    const THURSDAY = 'Thu';
    const FRIDAY = 'Fri';
    const SATURDAY = 'Sat';
    const SUNDAY = 'Sun';

    public function GetYeardays($dateStart, $dateEnd) {
        $dateEnd = date('Y-m-d', strtotime("+1 day", strtotime($dateEnd)));
        $period = new \DatePeriod(
                new \DateTime($dateStart), new \DateInterval('P1D'), (new \DateTime($dateEnd))
        );
        $dates = iterator_to_array($period);
        
        $arrayreturn = array();
        foreach ($dates as $val) {
            $date = $val->format('Y-m-d'); //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
            switch ($day_name) {
                case self::MONDAY:
                    $MONDAY[] = $date;
                    $arrayreturn[self::MONDAY] = $MONDAY;
                    break;
                case self::TUESDAY:
                    $TUESDAY[] = $date;
                    $arrayreturn[self::TUESDAY] = $TUESDAY;
                    break;
                case self::WEDENSDAY:
                    $WEDENSDAY[] = $date;
                    $arrayreturn[self::WEDENSDAY] = $WEDENSDAY;
                    break;
                case self::THURSDAY:
                    $THURSDAY[] = $date;
                    $arrayreturn[self::THURSDAY] = $THURSDAY;
                    break;
                case self::FRIDAY:
                    $FRIDAY[] = $date;
                    $arrayreturn[self::FRIDAY] = $FRIDAY;
                    break;
                case self::SATURDAY:
                    $SATURDAY[] = $date;
                    $arrayreturn[self::SATURDAY] = $SATURDAY;
                    break;
                case self::SUNDAY:
                    $SUNDAY[] = $date;
                    $arrayreturn[self::SUNDAY] = $SUNDAY;
                    break;
            }
        }
        return $arrayreturn;
    }

}
