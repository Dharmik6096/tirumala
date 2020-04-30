<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_basic_tax".
 *
 * @property integer $basic_tax_code
 * @property string $basic_tax_name
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblBasicTax extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_basic_tax';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['basic_tax_name', 'union_code'], 'required'],
                [['basic_tax_name'], 'unique'],
                [['basic_tax_name'], 'string', 'max' => 100],
                [['basic_tax_code', 'is_active', 'originating_type'], 'safe'],
                [['basic_tax_name', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['is_active'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'basic_tax_code' => Yii::t('app', 'Basic Tax Code'),
            'basic_tax_name' => Yii::t('app', 'Basic Tax Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getGrossAmount() {
        return $this->find()->where(['basic_tax_code' => 1])->select('basic_tax_code')->one();
    }

    public function checkGrossAmount() {
        return (trim(($this->basic_tax_code) == 1)) ? false : true;
    }

    public function basicTaxForDetail($tax_code) {
        $taxDetail = new TblTaxDetail();
        $tax_data = $taxDetail->find()->select(['basic_tax_code'])->where(['tax_code' => $tax_code, 'is_active' => 1])->all();
        $values = $this->find()->select(['basic_tax_code', 'basic_tax_name'])->where(['is_active' => 1])->andWhere(['not in', 'basic_tax_code', $tax_data])->all();
        $values = \yii\helpers\ArrayHelper::map($values, 'basic_tax_code', 'basic_tax_name');
        return $values;
    }

}
