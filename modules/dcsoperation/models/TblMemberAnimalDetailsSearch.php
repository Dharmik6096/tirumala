<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberAnimalDetails;

/**
 * TblMemberProvisionalFamilyDetailsSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails`.
 */
class TblMemberanimalDetailsSearch extends TblMemberAnimalDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'member_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'daily_milk_production', 'created_at', 'updated_at', 'animal_type_code', 'heifers_count', 'milch_animal_count', 'dry_animal_count', 'total_animal', 'originating_type'], 'safe'],
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
        $query = TblMemberAnimalDetails::find();

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
            'member_code' => $this->member_code,
        ]);

        return $dataProvider;
    }

}
