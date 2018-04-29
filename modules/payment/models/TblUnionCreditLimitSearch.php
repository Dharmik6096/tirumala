<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblUnionCreditLimit;

/**
 * TblUnionCreditLimitSearch represents the model behind the search form about `app\modules\payment\models\TblUnionCreditLimit`.
 */
class TblUnionCreditLimitSearch extends TblUnionCreditLimit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_credit_limit_code', 'credit_type', 'credit_value'], 'integer'],
            [['union_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblUnionCreditLimit::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['unionCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($request['TblUnionCreditLimitSearch']['wef_date'])) 
        { 
            $wef_date=date('Y-m-d',  strtotime($request['TblUnionCreditLimitSearch']['wef_date']));
            $query->andFilterWhere(['like','CAST(wef_date AS DATE)',$wef_date]);
        }
        
        if(!empty($this->wef_date)){
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'union_credit_limit_code' => $this->union_credit_limit_code,
            'credit_type' => $this->credit_type,
            'credit_value' => $this->credit_value,
//            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
