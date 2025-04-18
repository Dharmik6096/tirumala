<?php

namespace app\modules\bkgprocess\models;

use Yii;

/**
 * This is the model class for table "tbl_data_exchange_lock".
 *
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
 */
class TblDataExchangeLock extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_data_exchange_lock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['update_key', 'data_post_status', 'originating_type', 'process_name', 'process_code', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'resp_status', 'resp_desc', 'resp_msg', 'resp_param_1', 'resp_param_2', 'resp_param_3', 'resp_param_4'], 'safe'],
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
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
