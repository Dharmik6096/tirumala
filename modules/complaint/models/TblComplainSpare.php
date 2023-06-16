<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_spare".
 *
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
 */
class TblComplainSpare extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_spare';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_code', 'spare_code', 'qty', 'old_serial_no', 'remarks', 'new_serial_no', 'created_by', 'updated_by', 'originating_type', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

}
