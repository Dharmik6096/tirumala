<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_animal_inspector".
 *
 * @property integer $animal_inspector_code
 * @property string $union_code
 * @property string $ai_name
 * @property string $ai_mobile_no
 * @property string $ai_address
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblAnimalInspector extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_animal_inspector';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'originating_type', 'created_at', 'updated_at', 'union_code', 'ai_name', 'ai_mobile_no', 'ai_address', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active'], 'default', 'value' => 1],
                [['ai_mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'animal_inspector_code' => Yii::t('app', 'Animal Inspector Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'ai_name' => Yii::t('app', 'Name'),
            'ai_mobile_no' => Yii::t('app', 'Mobile No'),
            'ai_address' => Yii::t('app', 'Address'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
