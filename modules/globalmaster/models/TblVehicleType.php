<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_type".
 *
 * @property integer $vehicle_type_code
 * @property integer $is_active
 * @property string $vehicle_type_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblVehicleType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['vehicle_type_name'], 'required'],
            [['vehicle_type_name'],'unique', 'when' => function($model) {
                $validate = Yii::$app->general->valiadteUnique($model, 'vehicle_type_name', $this->vehicle_type_name);
                    if($validate==0){return true;}return false;
            },'skipOnEmpty'=> true],
            [['vehicle_type_name', 'created_by', 'updated_by', 'local_name'], 'string'],
            [['created_at', 'updated_at','vehicle_type_code'], 'safe'],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['vehicle_type_name'], function ($attribute, $params) {
                Yii::$app->general->validateName($this, $attribute,$params);
            },'skipOnEmpty'=> false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'vehicle_type_name' => Yii::t('app', 'Vehicle Type Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblVehicleTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVehicleTypeQuery(get_called_class());
    }
}
