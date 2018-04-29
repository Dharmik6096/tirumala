<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMilkDispatch;

/**
 * TblMilkDispatchSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMilkDispatch`.
 */
class TblMilkDispatchSearch extends TblMilkDispatch
{
    public $federation_code;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milk_dispatch_code','head_load_kms','dip_stick_reading_closing','dip_stick_reading_opening', 'federation_code', 'non_default_dispatch','acidity', 'avg_clr', 'avg_fat', 'avg_snf', 'density', 'dispatch_qty', 'freezing_point', 'lactose', 'protein', 'temp', 'water', 'nos_of_can', 'to_bmc_plant', 'milk_quality_type', 'milk_type', 'challan_no', 'chamber_no', 'created_at', 'deleted_at', 'destination_code', 'from_date', 'from_shift', 'route_no', 'seal_no', 'to_date', 'to_shift', 'updated_at', 'vehicle_in_time', 'vehicle_no', 'vehicle_out_time', 'created_by', 'dcs_code', 'deleted_by', 'sub_center_code', 'union_code', 'updated_by'], 'safe'],
//            [['acidity', 'avg_clr', 'avg_fat', 'avg_snf', 'density', 'dispatch_qty', 'freezing_point', 'lactose', 'protein', 'temp', 'water'], 'number'],
//            [['is_active', 'is_delete'], 'integer'],
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
        $query = TblMilkDispatch::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode','unionCode.federationCode'/*,'milkQualityType','milkType'*/]);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_milk_dispatch.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_milk_dispatch.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_milk_dispatch.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_milk_dispatch.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'acidity' => $this->acidity,
            'avg_clr' => $this->avg_clr,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'density' => $this->density,
            'dip_stick_reading_closing' => $this->dip_stick_reading_closing,
            'dip_stick_reading_opening' => $this->dip_stick_reading_opening,
            'dispatch_qty' => $this->dispatch_qty,
            'freezing_point' => $this->freezing_point,
            'tbl_milk_dispatch.is_active' => $this->is_active,
            'tbl_milk_dispatch.is_delete' => 0,
            'lactose' => $this->lactose,
            'non_default_dispatch' => $this->non_default_dispatch,
            'nos_of_can' => $this->nos_of_can,
            'protein' => $this->protein,
            'temp' => $this->temp,
            'to_bmc_plant' => $this->to_bmc_plant,
            'water' => $this->water,
        ]);
        
        $query->andFilterWhere(['like', 'milk_dispatch_code', $this->milk_dispatch_code])
            ->andFilterWhere(['like', 'challan_no', $this->challan_no])
            ->andFilterWhere(['like', 'milk_quality_type', $this->milk_quality_type])
            ->andFilterWhere(['like', 'milk_type', $this->milk_type])
            ->andFilterWhere(['like', 'chamber_no', $this->chamber_no])
            ->andFilterWhere(['like', 'destination_code', $this->destination_code])
            ->andFilterWhere(['like', 'from_shift', $this->from_shift])
            ->andFilterWhere(['like', 'from_date', (!empty($this->from_date)?date('Y-m-d', strtotime($this->from_date)):'')])
            ->andFilterWhere(['like', 'to_date', (!empty($this->to_date)?date('Y-m-d', strtotime($this->to_date)):'')])
            ->andFilterWhere(['like', 'route_no', $this->route_no])
            ->andFilterWhere(['like', 'seal_no', $this->seal_no])
            ->andFilterWhere(['like', 'to_shift', $this->to_shift])
            ->andFilterWhere(['like', 'vehicle_in_time', $this->vehicle_in_time])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'vehicle_out_time', $this->vehicle_out_time])
            ->andFilterWhere(['like', 'sub_center_code', $this->sub_center_code]);

        return $dataProvider;
    }
}
