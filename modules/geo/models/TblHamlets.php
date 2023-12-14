<?php

namespace app\modules\geo\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_hamlets".
 *
 * @property string $hamlet_code
 * @property string $created_at
 * @property string $hamlet_name
 * @property string $local_name
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $village_code
 *
 * @property TblFederations[] $tblFederations
 * @property TblVillages $villageCode
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 * @property TblUsers $updatedBy
 * @property TblUnions[] $tblUnions
 */
class TblHamlets extends ChildModel {

    public $state;
    public $district_name;
    public $sub_district_name;
    public $district;
    public $village_name;
    public $sub_district;
    public $param = 'hamlet';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_hamlets';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['hamlet_name','village_code'], 'required'],
            [['hamlet_code'], 'required','except'=>'importCsv'],
            [[ 'state', 'district', 'sub_district'], 'required', 'message' => Yii::t('app/validation', '{attribute} cannot be blank.'),'on'=>'add','except'=>'importCsv'],
            [['hamlet_code'], 'unique'],
            [['created_at', 'updated_at', 'state', 'district', 'sub_district', 'is_active'], 'safe'],
            [['hamlet_code'], 'string', 'max' => 9, 'min' => 8],
            [['hamlet_name'], 'string', 'max' => 100],
            //[['hamlet_name'], 'match', 'pattern' => '/^[a-zA-Z\/]*$/'],
            [['hamlet_name'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
            }, 'skipOnEmpty' => false],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['village_code'], 'string', 'max' => 6],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'state' => Yii::t('app', 'State'),
            'district' => Yii::t('app', 'District'),
            'sub_district' => Yii::t('app', 'Sub District'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            /* 'hamlet_code' => Yii::t('app', 'Hamlet Code'),
              'created_at' => Yii::t('app', 'Created At'),
              'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'), */
            'hamlet_name' => Yii::t('app', 'Hamlet Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            /*  'is_active' => Yii::t('app', 'Is Active'),
              'updated_at' => Yii::t('app', 'Updated At'),
              'created_by' => Yii::t('app', 'Created By'),
              'updated_by' => Yii::t('app', 'Updated By'), */
            'village_code' => Yii::t('app', 'Village Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getCreatedBy()
//    {
//        return $this->hasOne(TblUsers::className(), ['user_code' => 'created_by']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getDeletedBy()
//    {
//        return $this->hasOne(TblUsers::className());
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getUpdatedBy()
//    {
//        return $this->hasOne(TblUsers::className(), ['user_code' => 'updated_by']);
//    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @inheritdoc
     * @return TblHamletsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblHamletsQuery(get_called_class());
    }

    public function getRecord($hamletCode) {
        $val = $this->find()->select('hamlet_name,tbl_hamlets.village_code')->joinWith(['villageCode'])->where(['hamlet_code' => $hamletCode, 'tbl_hamlets.is_active'=>1])->one();
        return $val;
    }

    public function getMaxVillageCode($code) {
        $data=  $this->find()->select(["MAX(convert(int,substring(hamlet_code,7,3))) as hamlet_code"])->where(['village_code'=>trim($code)])->one();
        $new_code = isset($data['hamlet_code']) ? ($data['hamlet_code'] + 1) : 1;
        $final_code = $new_code<10 ? str_pad($new_code,2,'0',STR_PAD_LEFT): $new_code;
        return trim($code).$final_code ;
    }

}