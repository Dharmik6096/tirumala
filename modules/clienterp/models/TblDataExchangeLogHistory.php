<?php

namespace app\modules\clienterp\models;

use Yii;

/**
 * This is the model class for table "tbl_data_exchange_log".
 *
 * @property integer $id
 * @property integer $data_exchange_lock_code
 * @property string $process_name
 * @property string $process_code
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $resp_msg
 * @property string $response_datetime
 * @property string $resp_param_1
 * @property string $resp_param_2
 * @property string $resp_param_3
 * @property string $resp_param_4
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDataExchangeLogHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_data_exchange_log_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['data_exchange_log_code','process_name','process_code', 'update_key', 'data_post_status','picked_datetime','resp_status','resp_desc','resp_msg','response_datetime','resp_param_1','resp_param_2','resp_param_3','resp_param_4','resp_param_5','resp_param_6','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','history_created_at','history_created_by','operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'data_exchange_lock_code' => Yii::t('app', 'Data Exchange Lock Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'process_code' => Yii::t('app', 'Process Code'),
            'update_key' => Yii::t('app', 'Update Key'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'resp_msg' => Yii::t('app', 'Resp Msg'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'resp_param_1' => Yii::t('app', 'Resp Param 1'),
            'resp_param_2' => Yii::t('app', 'Resp Param 2'),
            'resp_param_3' => Yii::t('app', 'Resp Param 3'),
            'resp_param_4' => Yii::t('app', 'Resp Param 4'),
            'resp_param_5' => Yii::t('app', 'Resp Param 5'),
            'resp_param_6' => Yii::t('app', 'Resp Param 6'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
