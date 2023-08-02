<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_activity_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblComplainActivityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_activity_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['activity_type', 'remarks', 'user_code', 'location_details', 'entry_type', 'operation_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'history_created_by', 'created_at', 'updated_at', 'history_created_at', 'complain_activity_code', 'complain_code', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
