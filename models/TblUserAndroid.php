<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_android".
 *
 * @property string $user_id
 * @property string $name
 * @property string $password
 * @property string $mobile_no
 * @property string $email
 * @property integer $is_active
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $device_id
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 */
class TblUserAndroid extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_android';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required', 'except' => ['androidsync']],
            [['user_id', 'name', 'password', 'mobile_no', 'email', 'bmc_code', 'mcc_plant_code', 'created_by', 'updated_by', 'device_id', 'flg_sentbox_entry', 'sync_status'], 'safe'],
            [['is_active'], 'safe'],
            [['created_at', 'updated_at', 'sync_timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'password' => Yii::t('app', 'Password'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'is_active' => Yii::t('app', 'Is Active'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_id' => Yii::t('app', 'Device ID'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

}
