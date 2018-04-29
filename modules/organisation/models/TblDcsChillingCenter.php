<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "tbl_dcs_chilling_center".
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $is_active
 * @property string $name
 * @property integer $type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblDcsChillingCenter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_chilling_center';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'created_at', 'updated_at'], 'safe'],
            [[ 'type'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsChillingCenterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsChillingCenterQuery(get_called_class());
    }
   public function getchillingcenter($type){
     $value = $this->find()->select(['id','name','type'])->all();
        //$value = ArrayHelper::map($value, 'id', 'name');
        $a = ArrayHelper::map($value,'id',function($value){
            $type=($value['type']=='1')?'MCC':'Plant';
            return $value['name'].'-'.$type;
        });
        return $a;
    }

    public function getChillingCenterValue($value){
        $value = $this->find()->select(['id','name','type'])->where(['id'=>$value])->one();

        return $value;
    }
}
