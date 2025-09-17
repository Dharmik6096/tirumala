<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblMedicineStock;

/**
 * TblMedicineStockSearch represents the model behind the search form about `app\modules\veterinary\models\TblMedicineStock`.
 */
class TblMedicineStockSearch extends TblMedicineStock {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_stock_id', 'medicine_id', 'originating_type'], 'integer'],
            [['union_code', 'module_name', 'module_code', 'batch_no', 'expire_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['stock', 'rate'], 'number'],
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
        $query = TblMedicineStock::find();

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
            'medicine_id' => $this->medicine_id,
            'stock' => $this->stock,
            'expire_date' => !empty($this->expire_date) ? date('Y-m-d', strtotime($this->expire_date)) : NULL,
            'rate' => $this->rate,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'module_name', $this->module_name])
                ->andFilterWhere(['like', 'module_code', $this->module_code])
                ->andFilterWhere(['like', 'batch_no', $this->batch_no]);

        return $dataProvider;
    }

}
