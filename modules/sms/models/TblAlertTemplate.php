<?php

namespace app\modules\sms\models;

use Yii;

/**
 * This is the model class for table "tbl_alert_template".
 *
 * @property int $alert_template_id
 * @property string $receiver_type
 * @property string $message
 * @property string $header_info
 * @property string $module_type
 * @property int $language_code
 * @property string $union_code
 */
class TblAlertTemplate extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_alert_template';
    }

    /**
     * {@inheritdoc}
     */ 
    public function rules() {
        return [
            [['receiver_type', 'message', 'header_info', 'module_type', 'union_code', 'department', 'report_path', 'api_master_id'], 'safe'],
            [['language_code'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'alert_template_id' => 'Alert Template ID',
            'receiver_type' => 'Receiver Type',
            'message' => 'Message',
            'header_info' => 'Header Info',
            'module_type' => 'Module Type',
            'language_code' => 'Language Code',
            'union_code' => 'Union Code',
            'department' => 'Department',
            'api_master_id' => 'API Master ID',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TblAlertTemplateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblAlertTemplateQuery(get_called_class());
    }

    public function getTemplateData($module, $receiver = 'SMS', $union = '') {
        $query = $this->find()->where(['module_type' => $module, 'receiver_type' => $receiver]);
        if (!empty($union)) {
            $query->andWhere(['union_code' => $union]);
        }
        return $query->one();
    }

    public function getRecord() {
        return $this->find()
                        ->where(['module_type' => $this->module_type, 'union_code' => $this->union_code, 'receiver_type' => $this->receiver_type])
                        ->one();
    }

}
