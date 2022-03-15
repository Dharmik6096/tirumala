<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_approval_history".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $date
 * @property integer $shift_code
 * @property integer $collection_type
 * @property string $code
 * @property integer $is_approve
 * @property string $requested_by
 * @property string $approved_by
 * @property string $approve_date
 * @property string $allow_till_date
 * @property integer $valid_hours
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblCollectionApprovalHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_collection_approval_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid', 'date', 'approve_date', 'allow_till_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['shift_code', 'collection_type', 'is_approve', 'valid_hours', 'originating_type'], 'safe'],
                [['code', 'originating_org_type', 'originating_org_code'], 'safe'],
                [['requested_by', 'approved_by', 'history_created_by'], 'safe'],
                [['created_by', 'updated_by', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'date' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'code' => Yii::t('app', 'Code'),
            'is_approve' => Yii::t('app', 'Is Approve'),
            'requested_by' => Yii::t('app', 'Requested By'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approve_date' => Yii::t('app', 'Approve Date'),
            'allow_till_date' => Yii::t('app', 'Allow Till Date'),
            'valid_hours' => Yii::t('app', 'Valid Hours'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
