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
            'ATMOST' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head',
                    'sp_name' => 'sp_remuneration_payment_tdc',
                ],
            ],
            'EIPLCOMMON' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head',
                    'sp_name' => 'sp_remuneration_payment',
                ],
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code',
                    'sp_name' => 'sp_member_dcs_payment_processing_data',
                ],
                'transit_loss' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,dcs_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_bmc_transit_loss',
                ],
                'payment_installment_status' => [
                    'param' => 'bmc_code,from_datetime,customer_type,user_code',
                    'sp_name' => 'sp_payment_installment_status_update',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code',
                    'sp_name' => 'sp_tpt_primary_payment_new',
                ],
                'vsp_payment_disburse' => [
                    'param' => 'customer_type,bmc_code,applicable_for,payment_cycle_code,user_code,is_without_release,union_bank_payment_code',
                    'sp_name' => 'sp_disburse_vendor_payment',
                ],
                'secondary_tpt_payment' => [
                    'param' => 'union_code,from_date,to_date,user_code,transporter_code,vehicle_code',
                    'sp_name' => 'sp_tpt_secondary_payment',
                ],
                'member_payment_disburse' => [
                    'param' => 'union_code,bmc_code,payment_cycle_code,user_code,org_code,org_type,is_without_release,p_union_bank_payment_code',
                    'sp_name' => 'member_payment_disburse',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment',
                ],
                'mcc_payment_disburse' => [
                    'param' => 'mcc_plant_code,bmc_code,applicable_for,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_disburse_remuneration_payment',
                ],
                'bonus_payment_disburse' => [
                    'param' => 'union_code,bmc_code,payment_type,customer_type,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_bonus_payment_disburse',
                ],
                'party_payment' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'process_party_payment_comman',
                ],
                'party_payment_disburse' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'disburse_party_payment',
                ],
            ],
            'VARDDAN' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_varddan',
                ],
            ],
            'MMD' => [
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head',
                    'sp_name' => 'sp_remuneration_payment_mmd',
                ],
            ],
            'UMANG' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_umang',
                ],
            ],
            'CARGILL' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code',
                    'sp_name' => 'sp_member_payment_cargill',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_cargill',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head',
                    'sp_name' => 'sp_remuneration_payment_cargill',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_cargill',
                ],
                'secondary_tpt_payment' => [
                    'param' => 'union_code,from_date,to_date,user_code,transporter_code,vehicle_code',
                    'sp_name' => 'sp_tpt_secondary_payment_cargill',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_cargill',
                ],
                'mcc_payment_disburse' => [
                    'param' => 'mcc_plant_code,bmc_code,applicable_for,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_disburse_remuneration_payment_cargill',
                ],
            ],
            'KOTMALE' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code',
                    'sp_name' => 'sp_member_payment_cargill',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_cargill',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head',
                    'sp_name' => 'sp_remuneration_payment_cargill',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_cargill',
                ],
                'secondary_tpt_payment' => [
                    'param' => 'union_code,from_date,to_date,user_code,transporter_code,vehicle_code',
                    'sp_name' => 'sp_tpt_secondary_payment_cargill',
                ],
            ],
            'SNMILK' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_snmilk',
                ],
            ],
            'ITC' => [
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_itc',
                ],
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,process_stop_payment,user_code',
                    'sp_name' => 'sp_member_payment_itc',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_itc',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head,process_stop_payment,user_code',
                    'sp_name' => 'sp_remuneration_payment_itc',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_itc',
                ],
            ],
            'SAUMYA' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_saumya',
                ],
            ],
            'JGF' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_jgf',
                ],
            ],
            'VRS_NEWASA' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,process_stop_payment,user_code',
                    'sp_name' => 'sp_member_payment_vrs_newasa',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_vrs_newasa',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head,process_stop_payment,user_code',
                    'sp_name' => 'sp_remuneration_payment_vrs_newasa',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_vrs',
                ],
                'bonus_payment' => [
                    'param' => 'union_code,bmc_code,payment_type,customer_type,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_bonus_payment_process_vrs_newasa',
                ],
                'party_payment' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'process_party_payment_vrs_newasa',
                ],
                'party_payment_disburse' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'disburse_party_payment',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_vrs_newasa',
                ],
            ],
            'VRS_GLT' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,process_stop_payment,user_code',
                    'sp_name' => 'sp_member_payment_vrs_newasa',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_vrs_glt',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head,process_stop_payment,user_code',
                    'sp_name' => 'sp_remuneration_payment_vrs_newasa',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_vrs',
                ],
                'bonus_payment' => [
                    'param' => 'union_code,bmc_code,payment_type,customer_type,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_bonus_payment_process_vrs_newasa',
                ],
                'party_payment' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'process_party_payment_vrs_newasa',
                ],
                'party_payment_disburse' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'disburse_party_payment',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_vrs_newasa',
                ],
            ],
            'VRS_MLP' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,process_stop_payment,user_code',
                    'sp_name' => 'sp_member_payment_vrs_mlp',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_vrs_mlp',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head,process_stop_payment,user_code',
                    'sp_name' => 'sp_remuneration_payment_vrs_mlp',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_vrs',
                ],
                'bonus_payment' => [
                    'param' => 'union_code,bmc_code,payment_type,customer_type,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_bonus_payment_process_vrs_newasa',
                ],
                'party_payment' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'process_party_payment_vrs_newasa',
                ],
                'party_payment_disburse' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'disburse_party_payment',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_vrs_newasa',
                ],
            ],
            'VRS_SBD' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,process_stop_payment,user_code',
                    'sp_name' => 'sp_member_payment_vrs_sbd',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_vrs_sbd',
                ],
                'remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime,calculate_milk_recovey,calculate_other_head,process_stop_payment,user_code',
                    'sp_name' => 'sp_remuneration_payment_vrs_sbd',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_mcc_remuneration_payment_vrs',
                ],
                'bonus_payment' => [
                    'param' => 'union_code,bmc_code,payment_type,customer_type,from_datetime,to_datetime,user_code',
                    'sp_name' => 'sp_bonus_payment_process_vrs_newasa',
                ],
                'party_payment' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'process_party_payment_vrs_newasa',
                ],
                'party_payment_disburse' => [
                    'param' => 'payment_type,party_master_code,from_date,to_date',
                    'sp_name' => 'disburse_party_payment',
                ],
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_vrs_newasa',
                ],
            ],
            'ANIG' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type',
                    'sp_name' => 'sp_vsp_payment_anig',
                ],
            ],
            'DEVMILK' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_devmilk',
                ],
            ],
            'CHADDHA' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_Chadha',
                ],
            ],
            'SWARN' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_swarn_milk',
                ],
            ],
            'SHUDDH' => [
                'primary_tpt_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_date,to_date,user_code,transporter_code',
                    'sp_name' => 'sp_tpt_primary_payment_shuddh',
                ],
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_shuddh',
                ],
                'mcc_remuneration_payment' => [
                    'param' => 'union_code,plant_code,mcc_plant_code,bmc_code,from_datetime,to_datetime',
                    'sp_name' => 'sp_remuneration_payment_shuddh',
                ],
            ],
            'PARAM' => [
                'vsp_payment' => [
                    'param' => 'union_code,from_datetime,from_shift,to_datetime,to_shift,payment_cycle_code,bmc_code,customer_type,process_stop_payment,user_code',
                    'sp_name' => 'sp_vsp_payment_param',
                ],
            ],
            'ABT' => [
                'member_payment' => [
                    'param' => 'from_datetime,to_datetime,union_code,bmc_code,payment_cycle_code,user_code',
                    'sp_name' => 'sp_member_payment_abt',
                ],
            ],
        ];
    }

}
