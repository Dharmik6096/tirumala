<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentRestrict;

/**
 * TblMemberPaymentRestrictSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentRestrict`.
 */
class TblMemberPaymentRestrictSearch extends TblMemberPaymentRestrict {

    public $from_date, $to_date, $bmc_name, $dcs_name, $ex_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_payment_restrict_code', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'bmc_name', 'dcs_code', 'ex_code', 'dcs_name', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_date', 'to_date'], 'safe'],
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
        $query = TblMemberPaymentRestrict::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_payment_restrict', 'tbl_member_payment_restrict', 'tbl_member_payment_restrict');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_member_payment_restrict.wef_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_member_payment_restrict.wef_date', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere(['=', 'CAST(tbl_member_payment_restrict.wef_date as date)', !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL]);
        $query->andFilterWhere(['like', 'tbl_member_payment_restrict.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member_payment_restrict.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_name])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', $this->ex_code]);
        return $dataProvider;
    }

}
