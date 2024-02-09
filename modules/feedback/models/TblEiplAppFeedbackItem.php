<?php

namespace app\modules\feedback\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_eipl_app_feedback_item".
 *
 * @property integer $eipl_app_feedback_item_code
 * @property string $feedback_item_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblEiplAppFeedbackItem extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_feedback_item';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['feedback_item_name', 'created_by', 'updated_by'], 'string'],
                [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'eipl_app_feedback_item_code' => Yii::t('app', 'Eipl App Feedback Item Code'),
            'feedback_item_name' => Yii::t('app', 'Feedback Item Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['user_code' => 'created_by']);
    }

    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['user_code' => 'updated_by'])->alias('updateUser');
    }

}
