<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberFamilyDetails;

/**
 * TblMemberProvisionalFamilyDetailsSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails`.
 */
class TblMemberFamilyDetailsSearch extends TblMemberFamilyDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['age', 'union_code', 'local_family_member_name', 'dob', 'remarks', 'nominee_address', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'local_nominee_address', 'member_code', 'gender_code', 'family_member_name', 'guardian_name', 'local_guardian_name', 'relationship_code', 'is_nominee', 'originating_type', 'created_at', 'updated_at'], 'safe'],
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
        $query = TblMemberFamilyDetails::find();

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
