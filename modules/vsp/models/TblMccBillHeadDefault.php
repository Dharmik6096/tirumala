<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_bill_head_default".
 *
 * @property integer $default_bill_head_code
 * @property string $default_bill_head_name
 * @property integer $is_active
 */
class TblMccBillHeadDefault extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_default';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_active'], 'integer'],
            [['default_bill_head_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'default_bill_head_code' => 'Default Bill Head Code',
            'default_bill_head_name' => 'Default Bill Head Name',
            'is_active' => 'Is Active',
        ];
    }

}
