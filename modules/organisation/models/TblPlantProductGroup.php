<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_plant_product_group".
 *
 * @property integer $plant_product_group_code
 * @property string $plant_product_group_name
 * @property string $local_name
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPlantProductGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_plant_product_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_product_group_code'], 'required'],
            [['plant_product_group_code', 'is_active'], 'integer'],
            [['plant_product_group_name', 'local_name', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plant_product_group_code' => Yii::t('app', 'Plant Product Group Code'),
            'plant_product_group_name' => Yii::t('app', 'Plant Product Group Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblPlantProductGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblPlantProductGroupQuery(get_called_class());
    }
}
