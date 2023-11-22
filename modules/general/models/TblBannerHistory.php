<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_banner_history".
 *
 * @property integer $id
 * @property integer $banner_code
 * @property string $union_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $seq_no
 * @property string $title
 * @property string $banner_for
 * @property string $description
 * @property string $tap_operation
 * @property string $tap_event
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
class TblBannerHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banner_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['description', 'tap_operation', 'tap_event', 'title', 'union_code', 'banner_for', 'originating_org_code', 'originating_org_type', 'operation_type', 'created_by', 'updated_by', 'history_created_by', 'banner_code', 'seq_no', 'originating_type', 'from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'banner_code' => Yii::t('app', 'Banner Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'seq_no' => Yii::t('app', 'Seq No'),
            'title' => Yii::t('app', 'Title'),
            'banner_for' => Yii::t('app', 'Banner For'),
            'description' => Yii::t('app', 'Description'),
            'tap_operation' => Yii::t('app', 'Tap Operation'),
            'tap_event' => Yii::t('app', 'Tap Event'),
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
