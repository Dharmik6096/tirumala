<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMaterialMaster;

/**
 * TblMaterialMasterSearch represents the model behind the search form about `app\modules\organisation\models\TblMaterialMaster`.
 */
class TblMaterialMasterSearch extends TblMaterialMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['material_code', 'is_active', 'originating_type', 'material_name', 'local_name', 'union_code', 'ref_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblMaterialMaster::find();

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
            'material_code' => $this->material_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
                ->andFilterWhere(['like', 'local_name', $this->local_name])
                ->andFilterWhere(['like', 'ref_code', $this->ref_code]);

        return $dataProvider;
    }

}
