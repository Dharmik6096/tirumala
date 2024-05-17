<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMemberAnimalType;

/**
 * This is the model class for table "tbl_member_provisional_animal_details".
 *
 * @property integer $member_provisional_animal_detail_code
 * @property string $union_code
 * @property string $provisional_member_code
 * @property integer $animal_type_code
 * @property integer $heifers_count
 * @property integer $milch_animal_count
 * @property integer $dry_animal_count
 * @property integer $total_animal
 * @property string $daily_milk_production
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMemberProvisionalAnimalDetails extends ChildModel {

    public $no_of_heifers_count, $no_of_milch_animal_count, $no_of_dry_animal_count, $no_of_total_animal;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional_animal_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'provisional_member_code', 'daily_milk_production', 'animal_type_code', 'heifers_count', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'created_at', 'updated_at', 'milch_animal_count', 'dry_animal_count', 'total_animal', 'originating_type', 'no_of_heifers_count', 'no_of_milch_animal_count', 'no_of_dry_animal_count', 'no_of_total_animal'], 'safe'],
//                [[ 'daily_milk_production', 'heifers_count', 'milch_animal_count', 'dry_animal_count', 'total_animal'], 'required', 'on' => ['provisional_animal_detail']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_provisional_animal_detail_code' => Yii::t('app', 'Member Provisional Animal Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'provisional_member_code' => Yii::t('app', 'Provisional Member Code'),
            'animal_type_code' => Yii::t('app', 'Animal Type'),
            'heifers_count' => Yii::t('app', 'No. of Heifers'),
            'milch_animal_count' => Yii::t('app', 'No. of Milch Animals'),
            'dry_animal_count' => Yii::t('app', 'No. of Dry Animals'),
            'total_animal' => Yii::t('app', 'Total No. of Animals'),
            'daily_milk_production' => Yii::t('app', 'Milk Production(Liters/day)'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getMemberAnimals() {
        return $this->find()->where(['animal_type_code' => $this->animal_type_code, 'provisional_member_code' => $this->provisional_member_code])->one();
    }

    public function getAnimalData($pro_member_code) {
        return $this->find()->where(['provisional_member_code' => $pro_member_code])->all();
    }

    public function getAnimalTypeCode() {
        return $this->hasOne(TblMemberAnimalType::className(), ['animal_type_code' => 'animal_type_code']);
    }

}
