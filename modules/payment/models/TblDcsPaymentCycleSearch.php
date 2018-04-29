<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblDcsPaymentCycle;

/**
 * TblDcsPaymentCycleSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPaymentCycle`.
 */
class TblDcsPaymentCycleSearch extends TblDcsPaymentCycle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'created_at', 'created_by', 'from_date', 'to_date','union_code', 'updated_at', 'updated_by'], 'safe'],
            [['is_active', 'interval_value','lock_billing_process','lock_data'], 'safe'],
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
        $request = Yii::$app->request->queryParams;
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(Yii::$app->session->get('organizations_type')=='UNION')
        {
            $query->andWhere(['union_code'=>  explode(',', Yii::$app->session->get('Unions'))]);
        }
       // var_dump($query->all()); exit;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(!empty($request['TblDcsPaymentCycleSearch']['from_date'])) 
        { 
            $from_date=date('Y-m-d',  strtotime($request['TblDcsPaymentCycleSearch']['from_date']));
            $query->andFilterWhere(['like','CAST(from_date AS DATE)',$from_date]);
        }
        if(!empty($request['TblDcsPaymentCycleSearch']['to_date'])) 
        { 
            $to_date=date('Y-m-d',  strtotime($request['TblDcsPaymentCycleSearch']['to_date']));
            $query->andFilterWhere(['like','CAST(to_date AS DATE)',$to_date]);
        }

        // grid filtering conditions
        
        if(!empty($this->from_date))
            $query->andFilterWhere(['like', 'from_date', date('Y-m-d', strtotime($this->from_date))]);
        if(!empty($this->to_date))
            $query->andFilterWhere(['like', 'to_date', date('Y-m-d', strtotime($this->to_date))]);
        
        Yii::$app->general->filterByNumber($query,$this,['interval_value']);
        
        $query->andFilterWhere([
            
            //'from_date' => $this->from_date,
            //'to_date' => $this->to_date,
            //'lock_billing_process'=>$this->lock_billing_process,
            //'lock_data'=>$this->lock_data,
            'is_active' => $this->is_active,            
//            'interval_value' => $this->interval_value,            
        ]);
        
        $query->andFilterWhere(['like', 'lock_data', $this->lock_data])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
