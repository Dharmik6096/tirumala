<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_plant_product_group_details_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $detail_code
 * @property string $plant_code
 * @property integer $plant_product_group_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPlantProductGroupDetailsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_plant_product_group_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'plant_code', 'created_by', 'updated_by'], 'safe'],
            [['plant_product_group_code', 'is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'plant_product_group_code' => Yii::t('app', 'Plant Product Group Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
