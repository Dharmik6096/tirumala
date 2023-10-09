<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblTankerRate;

/**
 * TblTankerRateSearch represents the model behind the search form about `app\modules\tankermovement\models\TblTankerRate`.
 */
class TblTankerRateSearch extends TblTankerRate {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'purchase_rate_code', 'rate_for', 'wef_date', 'created_at', 'originating_org_type', 'description', 'shift_applicability', 'originating_org_code', 'rate_gen_method_code', 'updated_at', 'created_by', 'updated_by', 'reference_code', 'ts_rate'], 'safe'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = TblTankerRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        if(Yii::$app->session->get('organizations_type')=='UNION')
//        {
//            $query->andWhere(['union_code'=>  explode(',', Yii::$app->session->get('Unions'))]);
//        }

        if (Yii::$app->session->get('Unions') !== '' && empty($this->union_code)) {
            $query->andFilterWhere(['tbl_tanker_rate.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else {
            $query->andFilterWhere(['tbl_tanker_rate.union_code' => $this->union_code]);
        }

        $query->joinWith(['shiftApplicability', 'rateMethod']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_tanker_rate.is_active' => $this->is_active,
            'originating_org_type' => $this->originating_org_type,
        ]);

        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'tbl_rate_generate_method.method', $this->rate_gen_method_code]);
        // ->andFilterWhere(['like', 'tbl_rate_type.rate_type', $this->rate_type])
        // ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_applicability])
        //  ->andFilterWhere(['like', 'reference_code', $this->reference_code])
        //->andFilterWhere(['like', 'ts_rate', $this->ts_rate]);

        return $dataProvider;
    }
}
