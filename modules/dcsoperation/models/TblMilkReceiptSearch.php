<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMilkReceipt;

/**
 * TblMilkReceiptSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMilkReceipt`.
 */
class TblMilkReceiptSearch extends TblMilkReceipt
{
    public $federation_code;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milk_receipt_code','federation_code','is_active', 'is_delete', 'challan_no', 'created_at','nos_of_can', 'receipt_local_type', 'receipt_org_chilling_center', 'deleted_at','milk_quality_type_code','milk_type','amount', 'rate', 'receipt_acidity', 'receipt_clr', 'receipt_density', 'receipt_fat', 'receipt_freezing_point', 'receipt_lactose', 'receipt_protein', 'receipt_qty', 'receipt_snf', 'receipt_temp', 'receipt_water', 'from_date', 'from_shift', 'rate_calculation_date_time', 'receipt_org_code',  'to_date', 'to_shift', 'updated_at', 'created_by', 'dcs_code', 'deleted_by', 'receipt_org_id', 'sub_center_code', 'union_code', 'updated_by'], 'safe'],
//            [['amount', 'rate', 'receipt_acidity', 'receipt_clr', 'receipt_density', 'receipt_fat', 'receipt_freezing_point', 'receipt_lactose', 'receipt_protein', 'receipt_qty', 'receipt_snf', 'receipt_temp', 'receipt_water'], 'number'],
//            [['is_active', 'is_delete', 'nos_of_can', 'receipt_local_type', 'receipt_org_chilling_center'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblMilkReceipt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode','unionCode.federationCode']);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_milk_receipt.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_milk_receipt.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_milk_receipt.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_milk_receipt.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'amount' => $this->amount,
            'tbl_milk_receipt.is_active' => $this->is_active,
            'tbl_milk_receipt.is_delete' => 0,
            'nos_of_can' => $this->nos_of_can,
            'rate' => $this->rate,
            'rate_calculation_date_time' => $this->rate_calculation_date_time,
            'receipt_acidity' => $this->receipt_acidity,
            'receipt_clr' => $this->receipt_clr,
            'receipt_density' => $this->receipt_density,
            'receipt_fat' => $this->receipt_fat,
            'receipt_freezing_point' => $this->receipt_freezing_point,
            'receipt_lactose' => $this->receipt_lactose,
            'receipt_local_type' => $this->receipt_local_type,
            'receipt_protein' => $this->receipt_protein,
            'receipt_qty' => $this->receipt_qty,
            'receipt_snf' => $this->receipt_snf,
            'receipt_temp' => $this->receipt_temp,
            'receipt_water' => $this->receipt_water,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type' => $this->milk_type,
            'receipt_org_chilling_center' => $this->receipt_org_chilling_center,
        ]);

        $query->andFilterWhere(['like', 'milk_receipt_code', $this->milk_receipt_code])
            ->andFilterWhere(['like', 'challan_no', $this->challan_no])
            ->andFilterWhere(['like', 'from_shift', $this->from_shift])
            ->andFilterWhere(['like', 'from_date', (!empty($this->from_date)?date('Y-m-d', strtotime($this->from_date)):'')])
            ->andFilterWhere(['like', 'to_date', (!empty($this->to_date)?date('Y-m-d', strtotime($this->to_date)):'')])
            ->andFilterWhere(['like', 'receipt_org_code', $this->receipt_org_code])
            ->andFilterWhere(['like', 'to_shift', $this->to_shift])
            ->andFilterWhere(['like', 'receipt_org_id', $this->receipt_org_id]);

        return $dataProvider;
    }
}
