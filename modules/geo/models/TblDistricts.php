<?php

namespace app\modules\geo\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_districts".
 *
 * @property string $district_code
 * @property string $created_at
 * @property string $district_name
 * @property string $local_name
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $state_code
 * @property string $updated_by
 *
 * @property TblBankDistrict[] $tblBankDistricts
 * @property TblBanks[] $bankCodes
 * @property TblBankDistrictHistory[] $tblBankDistrictHistories
 * @property TblDcs[] $tblDcs
 * @property TblDcsHistory[] $tblDcsHistories
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblStates $stateCode
 * @property User $updatedBy
 * @property TblFederations[] $tblFederations
 * @property TblFederationsHistory[] $tblFederationsHistories
 * @property TblSubCenter[] $tblSubCenters
 * @property TblSubCenterHistory[] $tblSubCenterHistories
 * @property TblSubDistricts[] $tblSubDistricts
 * @property TblSubDistrictsHistory[] $tblSubDistrictsHistories
 * @property TblUnionDistrict[] $tblUnionDistricts
 * @property TblUnions[] $unionCodes
 * @property TblUnionDistrictHistory[] $tblUnionDistrictHistories
 * @property TblUnions[] $tblUnions
 * @property TblUnionsHistory[] $tblUnionsHistories
 */
class TblDistricts extends ChildModel {

    public $param = 'district';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_districts';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['district_code', 'district_name'], 'required'],
            [['district_code'], 'unique'],
            [['district_code'], 'validateCode', 'on' => ['importCsv']],
            ['district_code', 'compare', 'compareValue' => '000', 'operator' => '!=', 'type' => 'number', 'message' => Yii::t('app/validation', '{attribute} can not be "000".')],
            [['created_at', 'updated_at', 'is_active'], 'safe'],
            [['district_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "001"')],
            [['district_code'], 'string', 'max' => 3, 'min' => 3, 'tooLong' => Yii::t('app/validation', '{attribute} must be of 3 digit. e.g."001"'),
                'tooShort' => Yii::t('app/validation', '{attribute} must be of 3 digit. e.g."001"')],
            [['district_name'], 'string', 'max' => 100],
//            [['district_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['district_name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute, $params);
            }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute, $params);
            }, 'skipOnEmpty' => false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['state_code'], 'string', 'max' => 2],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_code']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],           
        ];
    }
    
    public function validateCode($attribute, $params) {
        if(!empty($this->district_code)){
            $this->state_code = Yii::$app->session->get('States');
        }
            return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'district_code' => Yii::t('app', 'District Code'),
            'district_name' => Yii::t('app', 'District Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            /*
              'is_active' => Yii::t('app', 'Is Active'),
              'updated_at' => Yii::t('app', 'Updated At'),
              'created_by' => Yii::t('app', 'Created By'),, */
            'state_code' => Yii::t('app', 'State'),
                // 'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBankDistricts() {
        return $this->hasMany(TblBankDistrict::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCodes() {
        return $this->hasMany(TblBanks::className(), ['bank_code' => 'bank_code'])->viaTable('tbl_bank_district', ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBankDistrictHistories() {
        return $this->hasMany(TblBankDistrictHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsHistories() {
        return $this->hasMany(TblDcsHistory::className(), ['district_code' => 'district_code']);
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
    public function getDeletedBy() {
        return $this->hasOne(User::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsHistories() {
        return $this->hasMany(TblFederationsHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenters() {
        return $this->hasMany(TblSubCenter::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenterHistories() {
        return $this->hasMany(TblSubCenterHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistricts() {
        return $this->hasMany(TblSubDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubDistrictsHistories() {
        return $this->hasMany(TblSubDistrictsHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionDistricts() {
        return $this->hasMany(TblUnionDistrict::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCodes() {
        return $this->hasMany(TblUnions::className(), ['union_code' => 'union_code'])->viaTable('tbl_union_district', ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionDistrictHistories() {
        return $this->hasMany(TblUnionDistrictHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsHistories() {
        return $this->hasMany(TblUnionsHistory::className(), ['district_code' => 'district_code']);
    }

    /**
     * @inheritdoc
     * @return TblDistrictsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDistrictsQuery(get_called_class());
    }

    public function getDistrict($state, $districtCode = '') {

        if (!empty($districtCode)) {
            $unionQuery = $this->find()
                    ->select(['district_code', 'district_name','local_name'])->where(['district_code' => $districtCode]);

            $query = $this->find()
                    ->select(['district_code', 'district_name','local_name'])
                    ->where(['state_code' => $state, 'is_active' => 1])
                    ->union($unionQuery);
        } else {
            $query = $this->find()
                    ->select(['district_code', 'district_name','local_name'])
                    ->where(['state_code' => $state, 'is_active' => 1]);
        }
        if (Yii::$app->session->get('Districts') !== '') {
            $query->andWhere(['tbl_districts.district_code' => explode(',', Yii::$app->session->get('Districts'))]);
        }
        $array = $query->all();
        $data = \yii\helpers\ArrayHelper::map($array, 'district_code',function($array, $key) {
                    if (!($array['local_name']=='' || $array['local_name']==null))
                        return $array['district_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['district_name'];
                });
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }   
}
