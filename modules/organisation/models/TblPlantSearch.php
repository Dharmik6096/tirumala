<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblPlant;

/**
 * TblPlantSearch represents the model behind the search form about `app\modules\organisation\models\TblPlant`.
 */
class TblPlantSearch extends TblPlant {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plant_code', 'contact_person', 'name', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'village_code', 'local_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'mobile_no', 'local_contact_person_name', 'email', 'description', 'capacity', 'valid_from'], 'safe'],
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
        $query = TblPlant::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]],
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (Yii::$app->session->get('Unions') !== '' && empty($this->union_code)) {
            $query->andFilterWhere([ 'tbl_plant.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else {
            $query->andFilterWhere(['tbl_plant.union_code' => $this->union_code]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        if(!empty($this->capacity)){
//            $query->joinWith(['capacity0']);
//            $query->andFilterWhere([
//            'tbl_capacity.value'=> $this->capacity
//        ]);
//        }
        if (!empty($this->capacity)) {
            $query->joinWith(['capacity0']);
            if (preg_match('/^[1-9][0-9]*$/', $this->capacity)) {
                $query->andFilterWhere(['tbl_capacity.value' => $this->capacity]);
            } else {
                $query->andFilterWhere(['like', 'tbl_capacity.value', $this->capacity]);
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'district_code', $this->district_code])
                ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
                ->andFilterWhere(['like', 'state_code', $this->state_code])
                ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'village_code', $this->village_code])
                ->andFilterWhere(['like', 'local_name', $this->local_name])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'local_contact_person_name', $this->local_contact_person_name])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

}
