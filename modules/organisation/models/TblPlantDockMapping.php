<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_plant_dock_mapping".
 *
 * @property integer $plant_dock_mapping_code
 * @property string $union_code
 * @property string $plant_code
 * @property integer $dock_no
 * @property string $dock_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblPlantDockMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_dock_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'dock_no', 'plant_code', 'dock_name', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'created_at', 'updated_at', 'originating_type'], 'safe'],
                [['dock_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['union_code', 'plant_code', 'dock_no', 'dock_name'], 'required'],
                [['dock_no'], 'integer', 'min' => 1, 'max' => 9, 'message' => Yii::t('app/validation', '{attribute} must be between 1 and 9.')],
                [['plant_code', 'dock_no'], 'unique', 'targetAttribute' => ['plant_code', 'dock_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'plant_dock_mapping_code' => Yii::t('app', 'Plant Dock Mapping Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'dock_no' => Yii::t('app', 'Doc No'),
            'dock_name' => Yii::t('app', 'Doc Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getDockList($plant_code) {
        $dockData = $this->find()->where(['plant_code' => $plant_code])->all();

        $user = ArrayHelper::map($dockData, 'dock_no', function($data) {
                    return $data->dock_name;
                });
        return $user;
    }

}
