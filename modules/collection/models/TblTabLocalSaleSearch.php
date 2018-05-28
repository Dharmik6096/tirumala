<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblTabLocalSale;

/**
 * TblTabLocalSaleSearch represents the model behind the search form about `app\modules\collection\models\TblTabLocalSale`.
 */
class TblTabLocalSaleSearch extends TblTabLocalSale {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['Id'], 'safe'],
            [['parentId', 'stationId', 'date', 'shift', 'milkType', 'quantityMode', 'createOnUtc'], 'safe'],
            [['quantity', 'rate', 'amount'], 'safe'],
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
        $query = TblTabLocalSale::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date, 126)', date('Y-m-d', strtotime($this->date))]);
        }

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->stationId]);
        return $dataProvider;
    }

}
