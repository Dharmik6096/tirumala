<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\assetmanagement\models\TblStoreLocation;

/**
 * TblStoreLocationSearch represents the model behind the search form about `app\modules\assetmanagement\models\TblStoreLocation`.
 */
class TblStoreLocationSearch extends TblStoreLocation {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['store_location_code', 'store_location_name', 'store_location_type', 'reference_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name', 'sloc_code'], 'safe'],
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
        $query = TblStoreLocation::find();

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
        $query->joinWith(['storeLocType']);
        // grid filtering conditions

        $query->andFilterWhere(['like', 'sloc_code', $this->sloc_code])
                ->andFilterWhere(['like', 'reference_code', $this->reference_code])
                ->andFilterWhere(['like', 'store_location_name', $this->store_location_name])
                ->andFilterWhere(['like', 'tbl_store_location_type.slt_name', $this->store_location_type])
                ->andFilterWhere(['like', 'store_location_code', $this->store_location_code]);


        return $dataProvider;
    }

}
