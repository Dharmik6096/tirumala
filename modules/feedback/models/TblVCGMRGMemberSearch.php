<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblVCGMRGMember;

/**
 * TblVCGMRGMemberSearch represents the model behind the search form about `app\modules\feedback\models\TblVCGMRGMember`.
 */
class TblVCGMRGMemberSearch extends TblVCGMRGMember
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VCG_MRG_member_id', 'originating_type'], 'integer'],
            [['mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'member_code', 'member_tr_code', 'wef_date', 'end_date', 'status', 'type', 'attachment_sign_key', 'attachment_photo_key', 'remark', 'approved_at', 'approved_by', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblVCGMRGMember::find();

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
            'VCG_MRG_member_id' => $this->VCG_MRG_member_id,
            'wef_date' => $this->wef_date,
            'end_date' => $this->end_date,
            'approved_at' => $this->approved_at,
            'transaction_date' => $this->transaction_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'member_tr_code', $this->member_tr_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'attachment_sign_key', $this->attachment_sign_key])
            ->andFilterWhere(['like', 'attachment_photo_key', $this->attachment_photo_key])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'approved_by', $this->approved_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
