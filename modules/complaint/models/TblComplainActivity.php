<?php

namespace app\modules\complaint\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\complaint\models\TblComplain;

/**
 * This is the model class for table "tbl_complain_activity".
 *
 * @property integer $complain_activity_code
 * @property integer $complain_code
 * @property string $activity_type
 * @property string $remarks
 * @property string $location_details
 * @property string $user_code
 * @property string $entry_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblComplainActivity extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_code', 'user_code', 'location_details', 'remarks', 'originating_type', 'created_at', 'updated_at', 'activity_type', 'entry_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_activity_code' => Yii::t('app', 'Complain Activity Code'),
            'complain_code' => Yii::t('app', 'Complain Code'),
            'activity_type' => Yii::t('app', 'Activity Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'location_details' => Yii::t('app', 'Location Details'),
            'user_code' => Yii::t('app', 'User Code'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getContactDetailsCodes() {
        return $this->hasOne(User::className(), ['id' => 'user_code']);
    }

    public function getComplainActivity() {
        return $this->hasOne(TblComplain::className(), ['complain_code' => 'complain_code']);
    }

}
