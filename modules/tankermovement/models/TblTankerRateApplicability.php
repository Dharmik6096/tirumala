<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\tankermovement\models\TblPartyMaster;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\tankermovement\models\TblTankerRate;
use app\modules\tankermovement\models\TblShift;

/**
 * This is the model class for table "tbl_tanker_rate_applicability".
 *
 * @property integer $rate_app_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $tanker_rate_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $shift_code
 * @property string $union_code
 * @property string $updated_by
 * @property integer $is_active

 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblTankerRateApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $rate_gen_method_code, $rate_description;
    public $purchase_rate;
    public $rate_type, $party_name;
    public $import_union_code, $import_eipl_code, $import_key_pattern;

    public static function tableName() {
        return 'tbl_tanker_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_active'], 'default', 'value' => '1'],
            [['shift_code'], 'default', 'value' => '1'],
            [['wef_date', 'shift_code'], 'required', 'except' => ['importCsv']],
            [['applicable_code'], 'required', 'message' => 'You must select atleast one party.',],
            [['applicable_code', 'applicable_for', 'is_active', 'created_at', 'shift_code', 'updated_at', 'wef_date'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
         //   ['applicable_code', 'unique', 'targetAttribute' => ['wef_date', 'shift_code', 'tanker_rate_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Tanker Rate applicability available for party for selected date and shift')],           
          ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_app_code' => Yii::t('app', 'Rate Apply ID'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'tanker_rate_code' => Yii::t('app', 'Rate ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'shift_code' => Yii::t('app', 'Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'party_name' => Yii::t('app', 'Applicable Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateCode() {
        return $this->hasOne(TblTankerRate::className(), ['tanker_rate_code' => 'tanker_rate_code']);
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
    public function getPartyCode() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'applicable_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateApplicabilityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblTankerRateApplicabilityQuery(get_called_class());
    }

    public function checkDuplicate() {
        $check = $this->find()->where(['shift_code' => $this->shift_code, 'tanker_rate_code' => $this->tanker_rate_code, 'wef_date' => $this->wef_date, 'applicable_code' => $this->applicable_code, 'is_active' => 1])->count();
        return $check;
    }

    public function getParty() {
        $values = $this->find()->select('applicable_code')->where(['tanker_rate_code' => $this->tanker_rate_code, 'is_active' => 1])->asArray()->all();
        $selected = ArrayHelper::getColumn($values, 'applicable_code');
        return $selected;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }
}
