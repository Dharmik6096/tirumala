<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use yii\db\Expression;
use yii\db\ActiveQuery;

/**
 * TblDcsPurchaseRateApplicabititySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity`.
 */
class TblDcsPurchaseRateApplicabititySearch extends TblDcsPurchaseRateApplicabitity {

    public $mcc_name, $code_ex, $shift_code, $rate_for;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //   [['rate_app_code', 'created_at', 'created_by', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'purchase_rate_code', 'union_code'], 'safe'],
            //   [['is_active'], 'boolean'],
            //   [['shift_code'], 'integer'],
            [['wef_date', 'applicable_for', 'mcc_name', 'applicable_code', 'applicable_for', 'code_ex', 'shift_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'rate_for', 'union_code', 'dcs_code', 'purchase_rate_code'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'rate_for'], 'required', 'on' => ['deleteApplicability']]
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
        $query = TblDcsPurchaseRateApplicabitity::find();
        $query->where(['tbl_dcs_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        // add conditions that should always apply here
        $query->joinWith(['customerMasterCode', 'dcsName', 'bmcCode', 'mccPlantCode', 'plantCode', 'customerType', 'shiftCode']);
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

        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(tbl_dcs_purchase_rate_applicability.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);
        // grid filtering conditions

        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_name', $this->mcc_name],
            ['like', 'tbl_customer_master.customer_name', $this->mcc_name],
            ['like', 'tbl_plant.name', $this->mcc_name],
            ['like', 'tbl_mcc_plant.name', $this->mcc_name],
            ['like', 'tbl_bmc.bmc_name', $this->mcc_name]
        ]);
        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_code_ex', $this->code_ex],
            ['like', 'tbl_customer_master.customer_code_ex', $this->code_ex]
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs_purchase_rate_applicability.applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
//                ->andFilterWhere(['like', 'tbl_dcs_purchase_rate_applicability.applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $MemberData = TblPurchaseRateApplicability::find()->select(['tbl_purchase_rate_applicability.union_code', 'tbl_purchase_rate_applicability.rate_app_code', 'tbl_purchase_rate_applicability.dcs_code as applicable_code', 'applicable_for' => new Expression("'DCS'"), 'tbl_purchase_rate_applicability.wef_date', 'rate_chart_for' => new Expression("'MEMBER'"), 'tbl_purchase_rate_applicability.purchase_rate_code', 'tbl_purchase_rate_applicability.shift_code', 'tbl_purchase_rate.description'])
                ->where(['tbl_dcs.bmc_code' => $this->bmc_code])
                ->andWhere('\'' . $this->rate_for . '\'=\'member\' or \'' . $this->rate_for . '\'=\'both\'')
                ->andFilterWhere(['tbl_purchase_rate_applicability.dcs_code' => $this->dcs_code, 'tbl_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code]);
        if (!empty($this->wef_date)) {
            $MemberData->andFilterWhere(['CAST(tbl_purchase_rate_applicability.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);
        }

        $MemberData->joinWith(['dcsCode', 'purchaseRateCode']);

        $query = $this->find()->select(['tbl_dcs_purchase_rate_applicability.union_code', 'tbl_dcs_purchase_rate_applicability.rate_app_code', 'tbl_dcs_purchase_rate_applicability.applicable_code', 'tbl_dcs_purchase_rate_applicability.applicable_for', 'tbl_dcs_purchase_rate_applicability.wef_date', 'rate_chart_for' => new Expression("'BMC'"), 'tbl_dcs_purchase_rate_applicability.purchase_rate_code', 'tbl_dcs_purchase_rate_applicability.shift_code', 'tbl_dcs_purchase_rate.description']);

        $query->andWhere('\'' . $this->rate_for . '\'=\'bmc\' or \'' . $this->rate_for . '\'=\'both\'');


        $unionQuery = (new ActiveQuery(TblDcsPurchaseRateApplicabitity::className()))->from([
                    'tbl_dcs_purchase_rate_applicability' => $query->union($MemberData, TRUE)
                ])->orderBy(['wef_date' => SORT_DESC]);

        // add conditions that should always apply here,'

        $query->joinWith(['dcsCode', 'mainCustomerCode', 'purchaseRateCode']);
        if (!empty($this->bmc_code)) {
            $query->andWhere(['or', ['tbl_dcs.bmc_code' => $this->bmc_code], ['tbl_customer_master.bmc_code' => $this->bmc_code]]);
        } else {
            $query->andWhere('0=1');
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        $query->andFilterWhere([
            'tbl_dcs_purchase_rate_applicability.applicable_for' => $this->applicable_for,
            'tbl_dcs_purchase_rate_applicability.applicable_code' => $this->applicable_code,
            'tbl_dcs_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code,
        ]);
        if (!empty($this->wef_date)) {
            $query->andFilterWhere(['CAST(tbl_dcs_purchase_rate_applicability.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $unionQuery,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
