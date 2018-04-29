<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblPlantProductGroup;

/**
 * This is the model class for table "tbl_plant_product_group_details".
 *
 * @property string $plant_code
 * @property integer $plant_product_group_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblPlant $plantCode
 * @property TblPlantProductGroup $plantProductGroupCode
 */
class TblPlantProductGroupDetails extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_plant_product_group_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_product_group_code', 'is_active'], 'integer'],
            [['plant_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code']],
            [['plant_product_group_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlantProductGroup::className(), 'targetAttribute' => ['plant_product_group_code' => 'plant_product_group_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plant_code' => Yii::t('app', 'Plant Code'),
            'plant_product_group_code' => Yii::t('app', 'Plant Product Group Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlantCode()
    {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlantProductGroupCode()
    {
        return $this->hasOne(TblPlantProductGroup::className(), ['plant_product_group_code' => 'plant_product_group_code']);
    }

    /**
     * @inheritdoc
     * @return TblPlantProductGroupDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblPlantProductGroupDetailsQuery(get_called_class());
    }
    
    public function getProductGroups($code)
    {
        $product_groups=  TblPlantProductGroup::find(['is_active'=>1])->all();
        $product_groups_name = [];
        foreach ($product_groups as $key=>$pg) {
            $k=$pg['plant_product_group_code'];
            ($pg['local_name'] == '') ? $product_groups_name[$k] = $pg['plant_product_group_name'] : $product_groups_name[$k] = $pg['plant_product_group_name'] . ' (' . $pg['local_name'] . ')';
        }
        $product_groups=  yii\helpers\ArrayHelper::map($product_groups,'plant_product_group_code','plant_product_group_name');
        //var_dump($product_groups);exit;
        $values = $this->find()->select('plant_product_group_code')->where(['plant_code' => $code->plant_code,'is_active'=>1])->asArray()->all();
        $selected = [];
        if(!empty($product_groups)){
             foreach ($product_groups as $key=>$row){
                    if(array_search($key, array_column($values, 'plant_product_group_code'))!==FALSE){
                            $selected[] = $key;
                        }
                    }
                }
         return ['product_groups'=>$product_groups_name,'selected'=>$selected];
    }
}
