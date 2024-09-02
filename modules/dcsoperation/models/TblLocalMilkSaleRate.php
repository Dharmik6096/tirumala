<?php

namespace app\modules\dcsoperation\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMilkClass;

/**
 * This is the model class for table "tbl_local_milk_sale_rate".
 *
 * @property string $local_sale_rate_code
 * @property string $created_at
 * @property double $rate
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSaleRate extends \app\models\ChildModel {

    public $applicable_for, $applicable_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_local_milk_sale_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_for', 'applicable_code', 'local_milk_rate_code'], 'safe'],
            [['wef_date', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'milk_quality_type_code'], 'safe'],
            [['rate'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'local_milk_sale_rate_code' => Yii::t('app', 'Local Milk Sale Rate Code'),
            'wef_date' => Yii::t('app', 'Effective From Date'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'rate' => Yii::t('app', 'Rate'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Organization Code'),
            'originating_org_type' => Yii::t('app', 'Originating Organization Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Extra Column 1'),
            'x_col2' => Yii::t('app', 'Extra Column 2'),
            'x_col3' => Yii::t('app', 'Extra Column 3'),
            'x_col4' => Yii::t('app', 'Extra Column 4'),
            'x_col5' => Yii::t('app', 'Extra Column 5'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass() {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblLocalMilkSaleRateQuery(get_called_class());
    }

}
