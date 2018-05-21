<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblFuelRateMaster;

/**
 * TblFuelRateMasterSearch represents the model behind the search form about `app\modules\transporter\models\TblFuelRateMaster`.
 */
class TblFuelRateMasterSearch extends TblFuelRateMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fuel_rate_code', 'fuel_type_code'], 'integer'],
            [['rate'], 'number'],
            [['wef_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fuel_rate_code' => $this->fuel_rate_code,
            'rate' => $this->rate,
            'wef_date' => $this->wef_date,
            'fuel_type_code' => $this->fuel_type_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
