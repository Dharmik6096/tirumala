<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;
use app\components\GeneralFunctions;

/**
 * This is the model class for table "tbl_bank_districts".
 *
 * @property string $bank_code
 * @property string $district_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by

 *
 * @property TblBanks $bankCode
 * @property TblDistricts $districtCode
 */
class TblBanksDistrictsMapping extends ChildModel {

    public $district_name, $local_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_district';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bank_code', 'district_code', 'local_name'], 'safe'],
            [['created_at', 'is_active', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['bank_code'], 'string', 'max' => 4],
            [['bank_code'], 'validateBank'],
            [['bank_code'], 'validateDistrict'],
            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
        ];
    }

    public function validateBank($attribute, $params) {

        if (!empty($this->bank_code)) {
            $bank = new TblBanks();
            $bank = $bank->getRecode($this->bank_code);
            if (!$bank) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->bank_code . "'" . ' is invalid.'));
                return false;
            } else if ($bank->nationalized_bank == 1) {
                $this->addError($attribute, Yii::t('app/validation', " '" . $this->bank_code . "'" . ' is nationalized bank. You can not map districts.'));
                return false;
            }
        }
    }

    public function validateDistrict($attribute, $params) {

        if (!empty($this->bank_code)) {
            $check = $this->find()->where(['bank_code' => $this->bank_code, 'district_code' => $this->district_code])->count();
            if ($check != 0) {
                $this->addError($attribute, Yii::t('app/validation', " Bank Code '" . $this->bank_code . "' and District Code '" . $this->district_code . "'" . ' has already been taken.'));
                return false;
            } else {
                $bankDistrict = $this->find()->select(['district_code'])->where(['bank_code' => $this->bank_code])->one();
                if ($bankDistrict) {
                    $state = TblDistricts::find()->select('state_code')->where(['district_code' => $this->district_code])->one();
                    if ($bankDistrict->districtCode->state_code != $state->state_code) {
                        $this->addError($attribute, Yii::t('app/validation', "You can not use other state's district '" . $this->district_code . "' for bank '" . $this->bank_code . "'."));
                        return false;
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bank_code' => Yii::t('app', 'Bank Code'),
            'district_code' => Yii::t('app', 'District Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @inheritdoc
     * @return TblBanksDistrictsMappingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBanksDistrictsMappingQuery(get_called_class());
    }

    public function alreadyExist($bank_code) {
        return $this->find()->select(['district_code', 'bank_code'])->where(['bank_code' => $bank_code])->all();
    }

    public function getBankList($districtCode) {
        $unionQuery = TblBanks::find()
                        ->select(['bank_code', 'bank_name', 'local_name'])
                        ->where(['nationalized_bank' => '1', 'is_active' => 1])
                        ->createCommand()->rawSql;
        $array = $this->find()->joinWith('bankCode', true, 'INNER JOIN')->select(['tbl_banks.bank_code', 'bank_name', 'local_name'])->where(['district_code' => $districtCode])->andWhere(['tbl_banks.is_active' => 1])->union($unionQuery)->asArray()->all();
        $list = ArrayHelper::map($array, 'bank_code', function($array, $key) {
                    if (!empty($array['local_name']))
                        return $array['bank_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['bank_name'];
                });
        asort($list, SORT_NATURAL | SORT_FLAG_CASE);
        return $list;
    }

    public function getDistrictUsed($bankCode, $districtCode) {

        $fed = TblFederations::find()->where(['bank_code' => $bankCode, 'district_code' => $districtCode])->count();
        $union = TblUnions::find()->where(['bank_code' => $bankCode, 'district_code' => $districtCode])->count();
        $dcs = TblDcs::find()->where(['bank_code' => $bankCode, 'district_code' => $districtCode])->count();
       

        if ($fed != 0 || $union != 0 || $dcs != 0)
            return 1;

        return 0;
    }

    public function getDistrict($bankCode) {
        $stateModel = new TblStates();
        $states = $stateModel->getActiveStates();
        $values = $this->find()->select('district_code')->where(['bank_code' => $bankCode, 'is_active' => 1])->asArray()->all();
        $selected = [];
        if (!empty($states)) {
            foreach ($states as $key => $row) {
                $district = new TblDistricts();
                $district_list[$key . '-' . $row] = $district->getDistrict($key);
                foreach ($district_list[$key . '-' . $row] as $key1 => $dis) {
                    if (array_search($key1, array_column($values, 'district_code')) !== FALSE) {
                        $selected[] = $key1;
                    }
                }
            }
        }

        return ['district_list' => $district_list, 'selected' => $selected];
    }

    public function getDistrictArray($bankCode, $state, $districtCode = '') {

        if (!empty($districtCode)) {
            $unionQuery = $this->find()
                    ->select(['tbl_bank_district.district_code', 'tbl_districts.district_name', 'local_name'])
                    ->innerJoin('tbl_districts', 'tbl_districts.district_code=tbl_bank_district.district_code')
                    ->where(['tbl_bank_district.bank_code' => $bankCode, 'tbl_districts.state_code' => $state, 'tbl_bank_district.district_code' => $districtCode]);

            $query = $this->find()
                    ->select(['tbl_bank_district.district_code', 'tbl_districts.district_name', 'local_name'])
                    ->innerJoin('tbl_districts', 'tbl_districts.district_code=tbl_bank_district.district_code')
                    ->where(['tbl_bank_district.bank_code' => $bankCode, 'tbl_districts.state_code' => $state, 'tbl_bank_district.is_active' => 1])
                    ->union($unionQuery);
        } else {
            $query = $this->find()
                    ->select(['tbl_bank_district.district_code', 'tbl_districts.district_name', 'local_name'])
                    ->innerJoin('tbl_districts', 'tbl_districts.district_code=tbl_bank_district.district_code')
                    ->where(['tbl_bank_district.bank_code' => $bankCode, 'tbl_districts.state_code' => $state, 'tbl_bank_district.is_active' => 1]);
        }
        if (Yii::$app->session->get('Districts') !== '') {
            $query->andWhere(['tbl_bank_district.district_code' => explode(',', Yii::$app->session->get('Districts'))]);
        }
        $array = $query->all();
        $data = \yii\helpers\ArrayHelper::map($array, 'district_code', function($array, $key) {
                    if (!($array['local_name']=='' || $array['local_name']==null))
                        return $array['district_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['district_name'];
                });
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getRecord($bankCode, $districtCode) {
        return $this->find()->where(['bank_code' => $bankCode, 'district_code' => $districtCode, 'is_active' => 1])->one();
    }

}
