<?php

namespace app\components;

use yii\base\Component;

class ClientPaymentConfig extends Component {

    public function processPayment($process, $data) {
        $client_code = \Yii::$app->session->get('eiplCode');
        $spdetail = $this->spdetail();
        $config = (isset($spdetail[$client_code]) && isset($spdetail[$client_code][$process])) ? $spdetail[$client_code][$process] : $spdetail['EIPLCOMMON'][$process];
        $param = explode(',', $config['param']);
        $str = '';
        $count = count($param);
        for ($i = 1; $i <= $count; $i++) {
            $str .= ':paramName' . $i . ',';
        }
        $str = substr($str, 0, -1);
        $command = \Yii::$app->db->createCommand("{CALL {$config['sp_name']}({$str})}");
        $i = 1;
        foreach ($param as $key => $value) {
            $command->bindValue(':paramName' . $i, $data[$value]);
            $i++;
        }
        return $command->execute();
    }

    public static function spdetail() {
        return [
            'GYAN' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_cpmilk',
                ],
            ],
            'EIPLCOMMON' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment',
                ],
            ],
        ];
    }

}
