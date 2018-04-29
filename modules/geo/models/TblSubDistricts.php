<?php

namespace app\modules\geo\models;

use Yii;
use app\components\GeneralFunctions;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_sub_districts".
 *
 * @property string $sub_district_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $sub_district_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $district_code
 * @property string $updated_by
 *
 * @property TblBranches[] $tblBranches
 * @property TblFederations[] $tblFederations
 * @property TblDistricts $districtCode
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblUnions[] $tblUnions
 * @property TblVillages[] $tblVillages
 */
class TblSubDistricts extends ChildModel {

    public $state;
    public $param = 'sub-district';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sub_districts';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sub_district_code', 'sub_district_name', 'district_code'], 'required'],
            [['state'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.'), 'on' => 'add'],
            [['sub_district_code'], 'unique'],
            ['sub_district_code', 'compare', 'compareValue' => '00000', 'operator' => '!=', 'type' => 'number', 'message' => Yii::t('app/validation', '{attribute} can not be "00000".')],
            [['created_at', 'updated_at', 'state', 'is_active'], 'safe'],
            [['sub_district_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "00001"')],
            [['sub_district_code'], 'string', 'max' => 5, 'min' => 5, 'tooLong' => Yii::t('app/validation', '{attribute} must be of 5 digit. e.g."00001"'),
                'tooShort' => Yii::t('app/validation', '{attribute} must be of 5 digit. e.g."00001"')],
            [['sub_district_name'], 'string', 'max' => 100],
            //[['sub_district_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['sub_district_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['district_code'], 'string', 'max' => 3],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
//            [['updated_By'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_By' => 'user_code']],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'state' => Yii::t('app', 'State'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'sub_district_name' => Yii::t('app', 'Sub District Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'district_code' => Yii::t('app', 'District'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches() {
        return $this->hasMany(TblBranches::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['subdistrict_code' => 'sub_district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillages() {
        return $this->hasMany(TblVillages::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @inheritdoc
     * @return TblSubDistrictsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSubDistrictsQuery(get_called_class());
    }

    public function getSubDistrict($district) {

        $array = $this->find()
                ->select(['tbl_sub_districts.sub_district_code', 'sub_district_name'])
                ->where(['district_code' => $district, 'tbl_sub_districts.is_active' => 1])
                ->all();
        $data = \yii\helpers\ArrayHelper::map($array, 'sub_district_code', 'sub_district_name');
        return $data;
    }

}