<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\dcsaccounting\models\TblTaxGroup;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_tax".
 *
 * @property integer $tax_code
 * @property integer $tax_group_code
 * @property string $tax_name
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
class TblTax extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tax';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tax_group_code', 'tax_name'], 'required'],
                [['tax_name'], 'string', 'max' => 100],
                [['tax_code', 'tax_group_code', 'is_active', 'originating_type'], 'integer'],
                [['tax_name', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
                [['created_at', 'updated_at'], 'safe'],
                [['tax_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblTaxGroup::className(), 'targetAttribute' => ['tax_group_code' => 'tax_group_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tax_code' => Yii::t('app', 'Tax Code'),
            'tax_group_code' => Yii::t('app', 'Tax Group'),
            'tax_name' => Yii::t('app', 'Tax Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union Code'),
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

    public function getTaxGroupCode() {
        return $this->hasOne(TblTaxGroup::className(), ['tax_group_code' => 'tax_group_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getTblTaxDetails() {
        return $this->hasMany(TblTaxDetail::className(), ['tax_code' => 'tax_code']);
    }

    public function getRecord($id) {
        return $this->findOne($id);
    }

    public function getActiveTax() {
        $values = $this->find()->where(['is_active' => 1])->all();
        $values = \yii\helpers\ArrayHelper::map($values, 'tax_code', 'tax_name');
        return $values;
    }

}
