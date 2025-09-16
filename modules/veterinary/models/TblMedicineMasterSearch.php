<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblMedicineMaster;

/**
 * TblMedicineMasterSearch represents the model behind the search form about `app\modules\veterinary\models\TblMedicineMaster`.
 */
class TblMedicineMasterSearch extends TblMedicineMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'originating_type'], 'integer'],
            [['medicine_name', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMedicineMaster::find();

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
        $query->andFilterWhere(['like', 'medicine_name', $this->medicine_name]);

        return $dataProvider;
    }

}
