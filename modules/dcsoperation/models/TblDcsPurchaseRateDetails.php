<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_details".
 *
 * @property integer $code
 * @property double $fat
 * @property double $rtpl
 * @property double $snf
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $purchase_rate_code
 * @property integer $rate_type_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblDcsPurchaseRateDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_purchase_rate_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fat', 'rtpl', 'snf'], 'number'],
            [['milk_quality_type_code', 'milk_type_code', 'purchase_rate_code', 'rate_type_code', 'originating_type'], 'integer'],
            [['originating_org_code', 'originating_org_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'fat' => Yii::t('app', 'Fat'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'snf' => Yii::t('app', 'Snf'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'rate_type_code' => Yii::t('app', 'Rate Type Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
