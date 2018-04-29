<?php

namespace app\modules\geo\models;

use Yii;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_villages".
 *
 * @property string $village_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $village_name
 * @property string $local_name
 * @property string $created_by
 * @property string $sub_district_code
 * @property string $updated_by
 *
 * @property TblBranches[] $tblBranches
 * @property TblFederations[] $tblFederations
 * @property TblHamlets[] $tblHamlets
 * @property TblUnions[] $tblUnions
 * @property TblVillageMiscellaneous[] $tblVillageMiscellaneouses
 * @property TblUsers $deletedBy
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblSubDistricts $subDistrictCode
  */
class TblVillages extends ChildModel {

    public $state;
    public $district;
    public $district_name;
    public $sub_district_name;
    public $param = 'village';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_villages';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['village_code', 'village_name'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.')],
            [['state', 'district','sub_district_code'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.'),'on'=>'add'],
            [['village_code'], 'unique'],
            ['village_code', 'compare', 'compareValue' => '000000', 'operator' => '!=', 'type' => 'number','message'=> Yii::t('app/validation', '{attribute} can not be "000000".')],
            [['created_at', 'updated_at', 'state', 'district', 'sub_district_code','is_active'], 'safe'],
            [['village_code'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "000001"')],
            [['village_code'], 'string', 'max' => 6, 'min' => 6,'tooLong' =>  Yii::t('app/validation', '{attribute} must be of 6 digit. e.g."000001"'),
            'tooShort' => Yii::t('app/validation', '{attribute} must be of 6 digit. e.g."000001"')],
            [['village_name'], 'string', 'max' => 100],
            //[['village_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['village_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['sub_district_code'], 'string', 'max' => 5],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_code']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'State' => Yii::t('app', 'state'),
            'district' => Yii::t('app', 'District'),
            'village_code' => Yii::t('app', 'Village Code'),
            /* 'created_at' => Yii::t('app', 'Created At'),
              'is_active' => Yii::t('app', 'Is Active'),
              'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
              'updated_at' => Yii::t('app', 'Updated At'), */
            'village_name' => Yii::t('app', 'Village Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
                /*  'created_by' => Yii::t('app', 'Created By'),
                  'updated_by' => Yii::t('app', 'Updated By'), */
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches() {
        return $this->hasMany(TblBranches::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHamlets() {
        return $this->hasMany(TblHamlets::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblVillageMiscellaneouses() {
        return $this->hasMany(TblVillageMiscellaneous::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    /**
     * @inheritdoc
     * @return TblVillagesQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblVillagesQuery(get_called_class());
    }

    public function getVillage($sub_district_code) {
        $array = $this->find()
                 ->select(['tbl_villages.village_code', 'village_name'])
                 ->where(['sub_district_code'=>$sub_district_code,'tbl_villages.is_active'=>1])
                 ->all();
            $data = \yii\helpers\ArrayHelper::map($array, 'village_code', 'village_name');

         return $data;

    }
    public function getDcsVillageList($union_code) {

        $list = TblUnionsDistrictMapping::find()->where(['union_code' => $union_code])->all();
        $return_array = [];
        foreach ($list as $row) {
            if (isset($row->singleDistrict)) {
                if (isset($row->singleDistrict->tblSubDistricts)) {
                    foreach ($row->singleDistrict->tblSubDistricts as $sub) {
                        if (isset($sub->tblVillages)) {
                            foreach ($sub->tblVillages as $v) {
                                $return_array[$v->village_code] = $v->village_name;
                            }
                            //$return_array[] = ArrayHelper::map($sub->tblVillages, 'village_code', 'village_name');
                        }
                    }
                }
            }
        }
        return $return_array;
    }

    public function getRecord($code){
        return $this->find()->select(['sub_district_code'])->where(['village_code'=>$code,'is_active'=>1])->one();
    }
}
