<?php

namespace app\modules\staffmanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetails;

/**
 * TblStaffMemberFamilyDetailsSearch represents the model behind the search form about `app\modules\staffmanagement\models\TblStaffMemberFamilyDetails`.
 */
class TblStaffMemberFamilyDetailsSearch extends TblStaffMemberFamilyDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_family_details_code', 'is_nominee', 'is_minor', 'is_active'], 'integer'],
            [['staff_member_code', 'family_member_name', 'relation_code', 'birth_date', 'gender_code', 'union_code', 'local_family_member_name', 'guardian_member_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblStaffMemberFamilyDetails::find();

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
            'staff_family_details_code' => $this->staff_family_details_code,
            'is_nominee' => $this->is_nominee,
            'is_minor' => $this->is_minor,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'staff_member_code', $this->staff_member_code])
            ->andFilterWhere(['like', 'family_member_name', $this->family_member_name])
            ->andFilterWhere(['like', 'relation_code', $this->relation_code])
            ->andFilterWhere(['like', 'birth_date', $this->birth_date])
            ->andFilterWhere(['like', 'gender_code', $this->gender_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'local_family_member_name', $this->local_family_member_name])
            ->andFilterWhere(['like', 'guardian_member_code', $this->guardian_member_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
