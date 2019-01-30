<?php

namespace app\modules\vendorapi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vendorapi\models\TblVendorApiData;

/**
 * TblVendorApiDataSearch represents the model behind the search form about `app\modules\vendorapi\models\TblVendorApiData`.
 */
class TblVendorApiDataSearch extends TblVendorApiData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'log_id'], 'integer'],
            [['parent_code_other', 'parent_code', 'master_code', 'master_name', 'master_type', 'date_1', 'date_2', 'time_1', 'time_2', 'time_3', 'time_4', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'contact_first_name', 'contact_middle_name', 'contact_last_name', 'address', 'address_2', 'email', 'mobile_no', 'type_2', 'bank_name', 'branch_name', 'bank_account_no', 'ifsc', 'type_of_data', 'union_code', 'service_type', 'username', 'password', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['capacity', 'route_length', 'from_fat', 'to_fat', 'fat_price', 'from_snf', 'to_snf', 'snf_price'], 'number'],
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
        $query = TblVendorApiData::find();

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
            'id' => $this->id,
            'date_1' => $this->date_1,
            'date_2' => $this->date_2,
            'time_1' => $this->time_1,
            'time_2' => $this->time_2,
            'time_3' => $this->time_3,
            'time_4' => $this->time_4,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
            'route_length' => $this->route_length,
            'log_id' => $this->log_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'from_fat' => $this->from_fat,
            'to_fat' => $this->to_fat,
            'fat_price' => $this->fat_price,
            'from_snf' => $this->from_snf,
            'to_snf' => $this->to_snf,
            'snf_price' => $this->snf_price,
        ]);

        $query->andFilterWhere(['like', 'parent_code_other', $this->parent_code_other])
            ->andFilterWhere(['like', 'parent_code', $this->parent_code])
            ->andFilterWhere(['like', 'master_code', $this->master_code])
            ->andFilterWhere(['like', 'master_name', $this->master_name])
            ->andFilterWhere(['like', 'master_type', $this->master_type])
            ->andFilterWhere(['like', 'state_code', $this->state_code])
            ->andFilterWhere(['like', 'district_code', $this->district_code])
            ->andFilterWhere(['like', 'sub_district_code', $this->sub_district_code])
            ->andFilterWhere(['like', 'village_code', $this->village_code])
            ->andFilterWhere(['like', 'hamlet_code', $this->hamlet_code])
            ->andFilterWhere(['like', 'contact_first_name', $this->contact_first_name])
            ->andFilterWhere(['like', 'contact_middle_name', $this->contact_middle_name])
            ->andFilterWhere(['like', 'contact_last_name', $this->contact_last_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'address_2', $this->address_2])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'type_2', $this->type_2])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'ifsc', $this->ifsc])
            ->andFilterWhere(['like', 'type_of_data', $this->type_of_data])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'service_type', $this->service_type])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
