<?php

namespace app\modules\product\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockPhysical;

/**
 * TblProductStockPhysicalSearch represents the model behind the search form about `app\models\TblProductStockPhysical`.
 */
class TblProductStockPhysicalSearch extends TblProductStockPhysical {

    public $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'qty', 'stock_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
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
        $query = TblProductStockPhysical::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!empty($this->stock_date)) {
            $stock_date = date('Y-m-d', strtotime($this->stock_date));
            $query->andFilterWhere(['like', 'stock_date', $stock_date]);
        }
        $query->andFilterWhere(['like', 'plant_code', $this->f_plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->f_mcc_code])
                ->andFilterWhere(['like', 'bmc_code', $this->f_bmc_code])
                ->andFilterWhere(['like', 'dcs_code', $this->f_dcs_code]);

        return $dataProvider;
    }

}
