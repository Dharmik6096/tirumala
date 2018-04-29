<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_dcs_purchase_rate".
 *
 * @property integer $purchase_rate_code
 * @property string $created_at
 * @property string $created_by
 * @property string $description
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property integer $rate_gen_method_code
 * @property integer $shift_applicability
 * @property integer $shift_id
 * @property integer $originating_type
 * @property string $union_code
 */
class TblDcsPurchaseRate extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_purchase_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'wef_date'], 'safe'],
            [['created_by', 'description', 'originating_org_code', 'originating_org_type', 'updated_by', 'union_code'], 'string'],
            [['is_active', 'is_delete', 'rate_gen_method_code', 'shift_applicability', 'shift_id', 'originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'rate_gen_method_code' => Yii::t('app', 'Rate Gen Method Code'),
            'shift_applicability' => Yii::t('app', 'Shift Applicability'),
            'shift_id' => Yii::t('app', 'Shift ID'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }
    
    public function purchaseRate($data){
        $rtpl_data = [];
        $model = new TblDcs();
        $union = $model->find()->select(['union_code'])->where(['dcs_code'=>$data['dcs_code'], 'is_active' => 1])->one();
        if(!empty($union)){
            $union_code = $union->union_code;

            $purchase_rate_code = $this->find()
                    ->where(['union_code' => $union_code, 'shift_applicability'=> [3,$data['shift']]])
                    ->andWhere(['<=','wef_date',$data['dt_date']])
                    ->orderBy('wef_date desc')
                    ->one();
            
            if(!empty($purchase_rate_code)){
                $purchase_rate_code = $purchase_rate_code->purchase_rate_code;

                $detail_model = new TblDcsPurchaseRateDetails();

                $rtpl_data = $detail_model->find()
                        ->select(['rtpl','purchase_rate_code'])
                        ->where(['purchase_rate_code'=>$purchase_rate_code,'fat'=>$data['fat'],'snf'=>$data['snf'],'milk_quality_type_code'=>$data['milk_quality_type'],'milk_type_code'=>$data['milk_type']])
                        ->one();
            }
        }
        
        return $rtpl_data;
    }
}
