<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblKmWiseRate;

/**
 * TblKmWiseRateSearch represents the model behind the search form about `app\modules\transporter\models\TblKmWiseRate`.
 */
class TblKmWiseRateSearch extends TblKmWiseRate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['km_code'], 'integer'],
            [['rate', 'from_km', 'to_km'], 'number'],
            [['wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by','vehicle_code'], 'safe'],
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
        $query = TblKmWiseRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_km_wise_rate.vehicle_code' => $this->vehicle_code,
            'tbl_km_wise_rate.km_code' => $this->km_code,
            'tbl_km_wise_rate.rate' => $this->rate,
            'tbl_km_wise_rate.from_km' => $this->from_km,
            'tbl_km_wise_rate.to_km' => $this->to_km,
            'tbl_km_wise_rate.wef_date' => $this->wef_date,
        ]);

        return $dataProvider;
    }
}
