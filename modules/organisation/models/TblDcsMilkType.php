<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\models\ChildModel;
use app\modules\configuration\models\TblUnionRatechartRange;

/**
 * This is the model class for table "tbl_dcs_milk_type".
 *
 * @property string $dcs_code
 * @property integer $milk_type_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblAnimalType $milkTypeCode
 */
class TblDcsMilkType extends ChildModel {

    public $app_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_milk_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'milk_type_code'], 'required'],
            [['dcs_code', 'milk_type_code'], 'required'],
            //[['milk_type_code'],'validateMilkType'],
            [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateMilkType($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => 'dcsImport'],
            [['dcs_code'], 'validateDcs', 'except' => 'dcsImport'],
            [['milk_type_code'], 'integer'],
            [['created_at', 'is_active', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['is_active'], 'default', 'value' => 1]
        ];
    }

    public function validateDcs($attribute, $params) {
        if (!empty($this->dcs_code)) {

            $milkType = new TblDcs();
            $data = $milkType->getDcs(Yii::$app->session->get('organizations_code'), $this->dcs_code);

            if (!$data) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->dcs_code . "'" . ' is invalid.'));
                return false;
            } else {
                $check = $this->find()->where(['dcs_code' => $this->dcs_code, 'milk_type_code' => $this->milk_type_code])->count();
                if ($check != 0) {
                    $this->addError($attribute, Yii::t('app/validation', " Dcs Code '" . $this->dcs_code . "' and Milk Type Code '" . $this->milk_type_code . "'" . ' has already been taken.'));
                    return false;
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
        ];
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
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @inheritdoc
     * @return TblDcsMilkTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsMilkTypeQuery(get_called_class());
    }

    public function addDcsMilkType($dcsCode, $milkTypeCode, $operation) {

        foreach ($milkTypeCode as $row) {
            $modelMilkType = new TblDcsMilkType();
            $modelMilkType->dcs_code = $dcsCode;
            $modelMilkType->milk_type_code = $row;
            Yii::$app->operation->defaults($modelMilkType, $operation);
            $modelMilkType->save();
        }
    }

    public function getRateChartRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['animal_type_code' => 'milk_type_code'])->where(['union_code' => $this->dcsCode->union_code, 'config_for' => $this->app_type]);
    }

    public function setChildTable($model, &$modelSave) {
        $dcsCode = TblDcs::findOne($model->dcs_code);
        if (!empty($dcsCode) && $dcsCode->default_milk_type != 7) {
            $dcsHistoryModel = new TblDcsHistory();
            Yii::$app->operation->history($dcsCode, $dcsHistoryModel, UPDATE);
            $milkTypeArray = [];
            $milkType = $model->find()->where(['dcs_code' => $model->dcs_code, 'is_active' => 1])->all();
            $milkTypeArray[] = $model;
            foreach ($milkType as $mt) {
                $milkTypeArray[] = $mt;
            }
            $dcsCode->default_milk_type = $dcsCode->setDefaultMilkType($milkTypeArray);
            $dcsCode->scenario = 'DcsMilkType';
            array_push($modelSave, $dcsCode);
            array_push($modelSave, $dcsHistoryModel);
        }
    }

}
