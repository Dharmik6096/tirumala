<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * TblPartyMasterSearch represents the model behind the search form about `app\modules\tankermovement\models\TblPartyMaster`.
 */
class TblPartyMasterSearch extends TblPartyMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['party_master_code', 'union_code', 'party_name', 'party_contact_no', 'party_address', 'owner_name', 'owner_contact_no', 'owner_email', 'owner_address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'pan_no', 'adhar_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'sap_vendor_code', 'is_sales_office'], 'safe'],
            [['is_active', 'originating_type'], 'integer'],
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
        $query = TblPartyMaster::find();

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
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'party_master_code', $this->party_master_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'party_name', $this->party_name])
            ->andFilterWhere(['like', 'party_contact_no', $this->party_contact_no])
            ->andFilterWhere(['like', 'owner_name', $this->owner_name])
            ->andFilterWhere(['like', 'owner_contact_no', $this->owner_contact_no])
            ->andFilterWhere(['like', 'owner_email', $this->owner_email])
            ->andFilterWhere(['like', 'sap_vendor_code', $this->sap_vendor_code]);

        return $dataProvider;
    }
}
