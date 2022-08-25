<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_device_master_history".
 *
 * @property string $device_master_code
 * @property string $tab_type
 * @property string $sr_no
 * @property string $mac_address
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblDeviceMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_master_code'], 'required'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_type'], 'safe'],
            [['device_master_code', 'tab_type', 'originating_org_type', 'originating_org_code'], 'safe'],
            [['sr_no', 'created_by', 'updated_by'], 'safe'],
            [['mac_address', 'remarks'], 'safe'],
            [['operation_type'], 'safe'],
            [['history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_master_code' => Yii::t('app', 'Device Master Code'),
            'tab_type' => Yii::t('app', 'Tab Type'),
            'sr_no' => Yii::t('app', 'Sr No'),
            'mac_address' => Yii::t('app', 'Mac Address'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
