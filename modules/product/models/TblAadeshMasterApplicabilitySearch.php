<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblAadeshMasterApplicability;

/**
 * TblAadeshMasterApplicabilitySearch represents the model behind the search form about `app\modules\product\models\TblAadeshMasterApplicability`.
 */
class TblAadeshMasterApplicabilitySearch extends TblAadeshMasterApplicability {

    public $name, $code_ex;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['aadesh_master_applicability_code', 'product_code', 'originating_type'], 'safe'],
            [['wef_date', 'aadesh_master_code', 'dcs_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'mcc_plant_code', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'plant_code', 'bmc_code'], 'safe'],
            [['sale_rate', 'name', 'code_ex', 'is_member_rate'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'is_member_rate'], 'required', 'on' => ['deleteApplicability']]
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
        $query = TblAadeshMasterApplicability::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['customerTypeFor', 'plantCode', 'mccPlantCode', 'bmcCode', 'dispDcsCode', 'mainCustomerCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['aadesh_master_code' => $this->aadesh_master_code]);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_name', $this->name],
            ['like', 'tbl_customer_master.customer_name', $this->name],
            ['like', 'tbl_plant.name', $this->name],
            ['like', 'tbl_mcc_plant.name', $this->name],
            ['like', 'tbl_bmc.bmc_name', $this->name]
        ]);

        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_code_ex', $this->code_ex],
            ['like', 'tbl_customer_master.customer_code_ex', $this->code_ex]
        ]);

        $query->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_aadesh_master_applicability.applicable_code', $this->applicable_code]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblAadeshMasterApplicability::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['dcsCode', 'mainCustomerCode']);
        if (!empty($this->bmc_code)) {
            $query->andWhere(['or', ['tbl_dcs.bmc_code' => $this->bmc_code], ['tbl_customer_master.bmc_code' => $this->bmc_code]]);
        } else {
            $query->andWhere('0=1');
        }

        $query->andFilterWhere([
//            'tbl_aadesh_master_applicability.plant_code' => $this->plant_code,
//            'tbl_aadesh_master_applicability.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_aadesh_master_applicability.applicable_for' => $this->applicable_for,
            'tbl_aadesh_master_applicability.applicable_code' => $this->applicable_code,
            'tbl_aadesh_master_applicability.is_member_rate' => $this->is_member_rate,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_aadesh_master_applicability');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
