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
    public $from_date, $to_date, $member_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VCG_MRG_member_id', 'originating_type', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'member_code', 'member_tr_code', 'wef_date', 'end_date', 'status', 'type', 'attachment_sign_key', 'attachment_photo_key', 'remark', 'approved_at', 'approved_by', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['from_date', 'to_date', 'member_name'], 'safe'],
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
        $query->joinWith(['mccPlantCode', 'bmcCode', 'dcsCode', 'memberCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs', 'tbl_dcs', 'tbl_VCG_MRG_member', 'tbl_VCG_MRG_member');

        if (!empty($this->wef_date)) {
            $wef_date = date('Y-m-d', strtotime($this->wef_date));
            $query->andFilterWhere(['cast(tbl_VCG_MRG_member.wef_date as date)' => $wef_date]);
        }
        if (!empty($this->end_date)) {
            $end_date = date('Y-m-d', strtotime($this->end_date));
            $query->andFilterWhere(['cast(tbl_VCG_MRG_member.end_date as date)' => $end_date]);
        }
        if (!empty($this->transaction_date)) {
            $transaction_date = date('Y-m-d', strtotime($this->transaction_date));
            $query->andFilterWhere(['cast(tbl_VCG_MRG_member.transaction_date as date)' => $transaction_date]);
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_VCG_MRG_member.wef_date as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_VCG_MRG_member.wef_date as date)', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_VCG_MRG_member.VCG_MRG_member_id' => $this->VCG_MRG_member_id,
            'tbl_VCG_MRG_member.route_code' => $this->route_code,
            'tbl_VCG_MRG_member.approved_at' => $this->approved_at,
        ]);

        $query->andFilterWhere(['like', 'tbl_VCG_MRG_member.member_code', $this->member_code])
            ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_name])
            ->andFilterWhere(['like', 'tbl_VCG_MRG_member.member_tr_code', $this->member_tr_code])
            ->andFilterWhere(['like', 'tbl_VCG_MRG_member.status', $this->status])
            ->andFilterWhere(['like', 'tbl_VCG_MRG_member.type', $this->type])
            ->andFilterWhere(['like', 'tbl_VCG_MRG_member.remark', $this->remark])
            ->andFilterWhere(['like', 'tbl_VCG_MRG_member.approved_by', $this->approved_by]);

        return $dataProvider;
    }
}
