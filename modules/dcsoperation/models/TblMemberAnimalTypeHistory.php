<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_animal_type".
 *
 * @property integer $animal_type_code
 * @property string $animal_type_name
 * @property string $union_code
 * @property integer $is_active
 * @property integer $is_milch
 * @property string $local_name
 * @property string $short_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMemberAnimalTypeHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_animal_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['animal_type_code', 'animal_type_name', 'animal_type_code', 'is_active', 'is_milch', 'animal_type_name', 'union_code', 'local_name', 'short_name', 'created_by', 'originating_org_code', 'originating_org_type', 'updated_by', 'originating_type', 'created_at', 'updated_at', 'operation_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'animal_type_name' => Yii::t('app', 'Animal Type Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_milch' => Yii::t('app', 'Is Milch'),
            'local_name' => Yii::t('app', 'Local Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getAnimal() {
        return $this->find()->where(['is_active' => 1, 'union_code' => $this->union_code])->all();
    }

}
