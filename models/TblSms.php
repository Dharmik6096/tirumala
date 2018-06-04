<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_sms".
 *
 * @property integer $sms_id
 * @property string $module_name
 * @property string $module_code
 * @property string $mobile_no
 * @property string $sms_txt
 * @property string $sms_status
 * @property string $sms_result
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $sms_msgid
 * @property string $sms_mobile
 */
class TblSms extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['module_name', 'module_code', 'mobile_no', 'sms_txt', 'sms_status', 'sms_result', 'union_code', 'created_by', 'updated_by', 'sms_msgid', 'sms_mobile'], 'string'],
            [['created_at', 'updated_at', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sms_id' => Yii::t('app', 'Sms ID'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'sms_txt' => Yii::t('app', 'Sms Txt'),
            'sms_status' => Yii::t('app', 'Sms Status'),
            'sms_result' => Yii::t('app', 'Sms Result'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'sms_msgid' => Yii::t('app', 'Sms Msgid'),
            'sms_mobile' => Yii::t('app', 'Sms Mobile'),
        ];
    }

    public function getData() {
        return $this->find()
                        ->where(['sms_status' => 'N'])
                        ->andWhere(['or', ['status' => 0], ['status' => NULL]])
                        ->andWhere(['and', ['IS NOT', 'mobile_no', NULL], ['<>', 'mobile_no', '']])
                        ->limit(2000)
                        ->orderby('created_at ASC')
                        ->all();
    }
    
    public function updateSmsStatus($value) {
        return $this->updateAll(['status' => 1], ['sms_id' => $value]);
    }

}
