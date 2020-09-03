<?php

namespace app\modules\transporter\models;

use app\modules\transporter\models\TblFuelRateMaster;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblFuelRateMasterSearch represents the model behind the search form about `app\modules\transporter\models\TblFuelRateMaster`.
 */
class TblFuelRateMasterSearch extends TblFuelRateMaster {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fuel_type_code'], 'safe'],
            [['rate'], 'number'],
            [['wef_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date'], 'safe'],
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
        $query = TblFuelRateMaster::find();
        $query->joinWith(['fuelType']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_fuel_rate_master', 'tbl_fuel_rate_master');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(wef_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(wef_date as date)', $to_date]);
        }
        $query->andFilterWhere([
            'rate' => $this->rate,
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
        ]);
        $query->andFilterWhere(['like', 'tbl_fuel_type_master.fuel_type', $this->fuel_type_code]);


        return $dataProvider;
    }

}
