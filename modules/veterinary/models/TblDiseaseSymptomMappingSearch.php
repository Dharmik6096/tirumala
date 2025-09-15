<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_disease_symptom_mapping".
 *
 * @property integer $disease_symptom_id
 * @property integer $disease_id
 * @property integer $symptom_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblDiseaseSymptomMappingSearch extends TblDiseaseSymptomMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['disease_id', 'symptom_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblDiseaseSymptomMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_ats' => SORT_DESC]],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'capacity' => $this->capacity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'disease_id' => $this->disease_id,
        ]);


//        $query->andFilterWhere(['like', 'from_dest', $this->from_dest]);
//               ->andFilterWhere(['like', 'route_code', $this->route_code])
//                ->andFilterWhere(['like', 'from_type', $this->from_type])
//                ->andFilterWhere(['like', 'to_type', $this->to_type])
//                ->andFilterWhere(['like', 'to_dest', $this->to_dest])
//                ->andFilterWhere(['like', 'created_by', $this->created_by])
//                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
