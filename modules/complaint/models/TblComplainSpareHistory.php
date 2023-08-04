<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_spare_history".
 *
 * @property integer $id
 * @property integer $complain_spare_code
 * @property integer $complain_code
 * @property integer $spare_code
 * @property integer $qty
 * @property string $old_serial_no
 * @property string $new_serial_no
 * @property string $remarks
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
class TblComplainSpareHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_spare_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['remarks', 'old_serial_no', 'new_serial_no', 'complain_spare_code', 'complain_code', 'spare_code', 'operation_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'history_created_by', 'qty', 'originating_type', 'created_at', 'updated_at', 'history_created_at', 'new_spare_status', 'old_spare_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'complain_spare_code' => Yii::t('app', 'Complain Spare Code'),
            'complain_code' => Yii::t('app', 'Complain Code'),
            'spare_code' => Yii::t('app', 'Spare Code'),
            'qty' => Yii::t('app', 'Qty'),
            'old_serial_no' => Yii::t('app', 'Old Serial No'),
            'new_serial_no' => Yii::t('app', 'New Serial No'),
            'remarks' => Yii::t('app', 'Remarks'),
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
