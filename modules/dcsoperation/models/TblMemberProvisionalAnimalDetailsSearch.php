<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberProvisionalAnimalDetails;

/**
 * TblMemberProvisionalFamilyDetailsSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails`.
 */
class TblMemberProvisionalAnimalDetailsSearch extends TblMemberProvisionalAnimalDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'provisional_member_code', 'daily_milk_production', 'animal_type_code', 'heifers_count', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'created_at', 'updated_at', 'milch_animal_count', 'dry_animal_count', 'total_animal', 'originating_type', 'no_of_heifers_count', 'no_of_milch_animal_count', 'no_of_dry_animal_count', 'no_of_total_animal'], 'safe'],
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
        $query = TblMemberProvisionalAnimalDetails::find();

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
            'provisional_member_code' => $this->provisional_member_code,
        ]);

        return $dataProvider;
    }

}
