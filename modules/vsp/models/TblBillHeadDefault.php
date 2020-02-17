<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_default".
 *
 * @property integer $default_bill_head_code
 * @property string $default_bill_head_name
 * @property integer $is_active
 */
class TblBillHeadDefault extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bill_head_default';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['default_bill_head_name'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'default_bill_head_code' => Yii::t('app', 'Default Bill Head Type'),
            'default_bill_head_name' => Yii::t('app', 'Default Bill Head Name'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
