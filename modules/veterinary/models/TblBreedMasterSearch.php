<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblBreedMaster;

/**
 * TblBreedMasterSearch represents the model behind the search form about `app\modules\veterinary\models\TblBreedMaster`.
 */
class TblBreedMasterSearch extends TblBreedMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['breed_id', 'originating_type'], 'integer'],
            [['breed_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblBreedMaster::find();

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
        $query->andFilterWhere(['like', 'breed_name', $this->breed_name]);

        return $dataProvider;
    }

}
