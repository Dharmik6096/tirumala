<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityAlias;

/**
 * TblDcsPurchaseRateApplicabitityAliasSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityAlias`.
 */
class TblDcsPurchaseRateApplicabitityAliasSearch extends TblDcsPurchaseRateApplicabitityAlias {

    public $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_app_alias_code', 'is_active', 'purchase_rate_code', 'shift_code', 'rate_type', 'rate_gen_method_code', 'is_download'], 'integer'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'union_code', 'download_date_time', 'applicable_code', 'applicable_for', 'error_desc'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'status'], 'safe'],
            [['union_code', 'plant_code'], 'required', 'on' => 'approvalApplicability']
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
    public function approvalsearch($params) {
        $this->load($params);
        $query = TblDcsPurchaseRateApplicabitityAlias::find();
        // add conditions that should always apply here
        $query->joinWith(['dcsCode', 'customerMasterCode']);

        $query->andWhere(['or', ['tbl_dcs.plant_code' => $this->plant_code], ['tbl_customer_master.plant_code' => $this->plant_code]]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $query->andWhere(['IS NOT', 'tbl_dcs_purchase_rate_applicability_alias.applicable_code', NULL]);
        if (!empty($this->status)) {
            $query->andWhere(['tbl_dcs_purchase_rate_applicability_alias.status' => $this->status]);
        } else {
            $query->andWhere(['or', ['is', 'tbl_dcs_purchase_rate_applicability_alias.status', NULL], ['tbl_dcs_purchase_rate_applicability_alias.status' => 0]]);
        }
        $query->andFilterWhere([
            'purchase_rate_code' => $this->purchase_rate_code,
//            'tbl_dcs.mcc_plant_code' => $this->mcc_plant_code,
//            'tbl_dcs.bmc_code' => $this->bmc_code,
        ]);
        $query->andFilterWhere(['or', ['tbl_dcs.mcc_plant_code' => $this->mcc_plant_code], ['tbl_customer_master.mcc_plant_code' => $this->mcc_plant_code]]);
        $query->andFilterWhere(['or', ['tbl_dcs.bmc_code' => $this->bmc_code], ['tbl_customer_master.bmc_code' => $this->bmc_code]]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
