<?php

namespace app\modules\details\models;

use Yii;

/**
 * This is the model class for table "tbl_contact_details_history".
 *
 * @property integer $id
 * @property integer $detail_code
 * @property string $module_name
 * @property string $module_code
 * @property string $contact_person
 * @property string $email
 * @property string $mobile_no
 * @property string $local_contact_person
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $is_active
 */
class TblContactDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_contact_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'detail_code', 'department', 'is_verified', 'from_date', 'to_date', 'primary_parent', 'secondary_parent'], 'safe'],
            [['module_name', 'module_code', 'firstname', 'lastname', 'surname', 'email', 'mobile_no', 'local_firstname', 'local_lastname', 'local_surname', 'created_by', 'updated_by', 'operation_type', 'firstname', 'lastname', 'surname', 'local_firstname', 'local_lastname', 'local_surname'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'is_active', 'is_default', 'history_created_by', 'remarks', 'email_to', 'email_cc', 'email_bcc', 'x_col1', 'x_col2', 'x_col3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'detail_code' => Yii::t('app', 'Detail Code'),
                  'module_name' => Yii::t('app', 'Module Name'),
                  'module_code' => Yii::t('app', 'Module Code'),
                  'contact_person' => Yii::t('app', 'Contact Person'),
                  'email' => Yii::t('app', 'Email'),
                  'mobile_no' => Yii::t('app', 'Mobile No'),
                  'local_contact_person' => Yii::t('app', 'Local Contact Person'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'operation_type' => Yii::t('app', 'Operation Type'), */
        ];
    }

}
