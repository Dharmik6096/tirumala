<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_provisional_animal_details_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMemberProvisionalAnimalDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional_animal_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['daily_milk_production', 'provisional_member_code', 'operation_type', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_by', 'history_created_by', 'union_code', 'updated_at', 'history_created_at', 'member_provisional_animal_detail_code', 'animal_type_code', 'heifers_count', 'milch_animal_count', 'dry_animal_count', 'total_animal', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_provisional_animal_detail_code' => Yii::t('app', 'Member Provisional Animal Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'provisional_member_code' => Yii::t('app', 'Provisional Member Code'),
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'heifers_count' => Yii::t('app', 'Heifers Count'),
            'milch_animal_count' => Yii::t('app', 'Milch Animal Count'),
            'dry_animal_count' => Yii::t('app', 'Dry Animal Count'),
            'total_animal' => Yii::t('app', 'Total Animal'),
            'daily_milk_production' => Yii::t('app', 'Daily Milk Production'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
