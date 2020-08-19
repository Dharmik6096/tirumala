<?php

namespace app\components;

use yii\base\Component;

class EIPLPacketConfig extends Component {
    /*
     * validate No.of files
     * validate total size of all files
     * decide shift,date,stationid in packet or header or file name ?
     * file name validation strict binding 
     *  8 bit - first 2 char must be between 01 to 31
     *        - extension must be EIP
     *        - last digit must be M/E
     *        - length must be 11
     * 32 bit - first 3 char must be in difined keyword
     *        - than remove first 3 char and _ 
     *        - now it will be same as 8 bit name validate same as 8 bit     
     * based on selected union + dpu type get dpu key             
     * read file line by line
     * parse packet with key
     * trim packet (for header) and decide length
     * now pick config based on dpu_type + length
     * parse packet based on length and save data into packet log table
     * preview same data and on save copy to txfarmer table will be created on portal
     * call schedule of txfarmer
     * 
     */

    public function ConfigList($dpu_type) {
        $config = [
            '8' => [
                'endline' => 'ENDENDEND',
                'is_file_date' => true,
                '29' => [
                    'vlccid' => 'pckt=14-12',
                    'savelog' => false,
                ],
                '25' => [
                    'vlccid' => 'pckt=9-12',
                    'savelog' => false,
                ],
                '32' => [
                    'vlccid' => 'pckt=14-12',
                    'savelog' => false,
                ],
                '34#3' => [
                    'farmerid' => 'pckt=0-3',
                    'milktype' => 'pckt=3-1',
                    'fat' => 'pckt=4-2#fix=.#pckt=6-1',
                    'snf' => 'pckt=7-2#fix=.#pckt=9-1',
                    'water' => 'pckt=10-2',
                    'qty' => 'pckt=12-3#fix=.#pckt=15-2',
                    'amt' => 'pckt=17-5#fix=.#pckt=22-2',
                    'sampletime' => 'pckt=26-2#fix=:#pckt=24-2#fix=:00',
                    'txflag' => 'pckt=28-3',
                ],
                '29#3' => [
                    'farmerid' => 'pckt=0-3',
                    'milktype' => 'pckt=3-1',
                    'fat' => 'pckt=4-2#fix=.#pckt=6-1',
                    'snf' => 'pckt=7-2#fix=.#pckt=9-1',
                    'water' => 'pckt=10-2',
                    'qty' => 'pckt=12-3#fix=.#pckt=15-2',
                    'sampletime' => 'pckt=19-2#fix=:#pckt=17-2#fix=:00',
                    'txflag' => 'pckt=21-3',
                ],
                '44#3' => [
                    'farmerid' => 'pckt=0-3',
                    'milktype' => 'pckt=3-1',
                    'fat' => 'pckt=4-2#fix=.#pckt=6-1',
                    'snf' => 'pckt=7-2#fix=.#pckt=9-1',
                    'water' => 'pckt=10-2',
                    'qty' => 'pckt=12-3#fix=.#pckt=15-2',
                    'amt' => 'pckt=17-5#fix=.#pckt=22-2',
                    'sampletime' => 'pckt=26-2#fix=:#pckt=24-2#fix=:00',
                    'txflag' => 'pckt=28-3',
                ],
                '64#3' => [
                    'farmerid' => 'pckt=0-3',
                    'milktype' => 'pckt=3-1',
                    'fat' => 'pckt=4-2#fix=.#pckt=6-1',
                    'snf' => 'pckt=7-2#fix=.#pckt=9-1',
                    'water' => 'pckt=10-2',
                    'qty' => 'pckt=12-3#fix=.#pckt=15-2',
                    'amt' => 'pckt=17-5#fix=.#pckt=22-2',
                    'sampletime' => 'pckt=26-2#fix=:#pckt=24-2#fix=:00',
                    'txflag' => 'pckt=28-3',
                    'farmername' => 'pckt=31-12',
                ],
                '64#4' => [
                    'farmerid' => 'pckt=0-4',
                    'milktype' => 'pckt=4-1',
                    'fat' => 'pckt=5-2#fix=.#pckt=7-1',
                    'snf' => 'pckt=8-2#fix=.#pckt=10-1',
                    'water' => 'pckt=11-2',
                    'qty' => 'pckt=13-3#fix=.#pckt=16-2',
                    'amt' => 'pckt=18-5#fix=.#pckt=23-2',
                    'rate' => 'pckt=29-2#fix=.#pckt=31-2',
                    'sampletime' => 'pckt=27-2#fix=:#pckt=25-2#fix=:00',
                    'txflag' => 'pckt=33-3',
                    'farmername' => 'pckt=36-12',
                ],
            /*  '72' => [
              'vlccid' => '0-12',
              'farmerid' => '23-4',
              'milktype' => '27-1',
              'fat' => '28-2#.#30-1',
              'snf' => '31-2#.#33-1',
              'water' => '34-2',
              'qty' => '36-3#.#39-2',
              'amt' => '41-5#.#46-2',
              'rate' => '52-2#.#54-2',
              'shift' => '21-1',
              'dtdate' => '13-8',
              'sampletime' => 'dtdate#50-2#:#48-2',
              'txflag' => '56-3',
              'farmername' => '59-13',
              ], */
            ],
            '32' => [
                '144' => [
                    'vlccid' => 'pckt=3-15',
                    'shift' => 'pckt=127-1',
                    'dtdate' => 'pckt=67-6',
                    'farmerid' => 'pckt=19-4',
                    'milktype' => 'pckt=24-1',
                    'fat' => 'pckt=26-5',
                    'snf' => 'pckt=32-5',
                    'water' => 'pckt=38-4',
                    'qty' => 'pckt=43-6',
                    'qtymode' => 'pckt=50-1',
                    'rate' => 'pckt=52-6',
                    'amt' => 'pckt=59-7',
                    'sampletime' => 'pckt=73-2#fix=:#pckt=75-2#fix=:#pckt=77-2',
                    'txflag' => 'pckt=80-1#pckt=82-1#pckt=84-1',
                    'sampleno' => 'pckt=86-4',
                    'farmername' => 'pckt=91-16',
                ],
            ]
        ];
        return $config[$dpu_type];
    }

}

?> 