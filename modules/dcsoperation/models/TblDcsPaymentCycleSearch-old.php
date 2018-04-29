<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPaymentCycle;

/**
 * TblDcsPaymentCycleSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPaymentCycle`.
 */
class TblDcsPaymentCycleSearch extends TblDcsPaymentCycle
{
    public $federation_code;
    public $union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['federation_code','union_code','dcs_payment_cycle_code', 'created_at', 'deleted_at','from_date', 'from_shift', 'to_date', 'to_shift', 'updated_at', 'created_by', 'dcs_code', 'deleted_by', 'updated_by'], 'safe'],
            [['interval_value', 'is_active', 'is_billing', 'is_delete', 'lock_billing_process'], 'integer'],
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
        $query = TblDcsPaymentCycle::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode','dcsCode.unionCode','dcsCode.unionCode.federationCode']);
        
        $this->load($params);

        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_unions.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_unions.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_dcs_payment_cycle.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_dcs_payment_cycle.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'interval_value' => $this->interval_value,
            'tbl_dcs_payment_cycle.is_active' => $this->is_active,
            'tbl_dcs_payment_cycle.is_billing' => $this->is_billing,
            'tbl_dcs_payment_cycle.is_delete' => 0,
            'lock_billing_process' => $this->lock_billing_process,
        ]);

        if(!empty($this->from_date))
            $query->andwhere(['from_date' => date('Y-m-d', strtotime($this->from_date))]);
        
        if(!empty($this->to_date))
            $query->andwhere(['to_date' => date('Y-m-d', strtotime($this->to_date))]);
        
        $query->andFilterWhere(['like', 'tbl_dcs_payment_cycle.dcs_payment_cycle_code', $this->dcs_payment_cycle_code])
            ->andFilterWhere(['like', 'from_shift', $this->from_shift])
            ->andFilterWhere(['like', 'to_shift', $this->to_shift]);

        return $dataProvider;
    }
}
