<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblCustomerMaster;
use yii\data\ArrayDataProvider;

/**
 * TblCustomerMasterSearch represents the model behind the search form about `app\modules\organisation\models\TblCustomerMaster`.
 */
class TblCustomerMasterSearch extends TblCustomerMaster {

    public $master_type, $remark;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['customer_code', 'customer_name', 'address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'local_name', 'local_address', 'gst_no', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type', 'customer_code_ex', 'route_code', 'ref_code', 'aadhaar_no', 'master_type'], 'safe'],
                [['is_active', 'originating_type'], 'integer'],
                [['bmc_code', 'mcc_plant_code', 'plant_code', 'ts_code_m', 'ts_code_e', 'is_aadhar_verify'], 'safe'],
                [['customer_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'on' => ['deleteMapRoute']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'on' => ['verification']],
                [['union_code', 'plant_code', 'master_type'], 'required', 'on' => ['mmd-verification']]
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
        $query = TblCustomerMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_customer_master', 'tbl_customer_master', 'tbl_customer_master');


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_customer_master.customer_type' => $this->customer_type,
            'tbl_customer_master.is_active' => $this->is_active,
            'tbl_customer_master.route_code' => $this->route_code,
            'tbl_customer_master.x_col2' => $this->x_col2,
        ]);

        $query->andFilterWhere(['like', 'tbl_customer_master.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_master.customer_code_ex', $this->customer_code_ex])
                ->andFilterWhere(['like', 'tbl_customer_master.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_customer_master.customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'tbl_customer_master.gst_no', $this->gst_no])
                ->andFilterWhere(['like', 'tbl_customer_master.sap_code', $this->sap_code])
                ->andFilterWhere(['like', 'tbl_customer_master.refference_code', $this->refference_code])
                ->andFilterWhere(['like', 'tbl_customer_master.aadhaar_no', $this->aadhaar_no])
                ->andFilterWhere(['like', 'tbl_customer_master.ts_code_m', $this->ts_code_m])
                ->andFilterWhere(['like', 'tbl_customer_master.ts_code_e', $this->ts_code_e]);

        return $dataProvider;
    }

    public function deleteroutemapsearch($params) {
        $this->load($params);
        $query = TblCustomerMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['routeCode'], true, 'INNER JOIN');
        $query->andWhere(['tbl_customer_master.bmc_code' => $this->bmc_code]);

        $query->andFilterWhere([
            'tbl_customer_master.plant_code' => $this->plant_code,
            'tbl_customer_master.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_customer_master.customer_type' => $this->customer_type,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_customer_master');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'tbl_customer_master.route_code', $this->route_code]);

        return $dataProvider;
    }

    public function verificationsearch($params) {
        $this->load($params);
        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'datetime' => date('Y-m-d'),
                'master_type' => ''];
            $sp = 'portal_master_data_verification';
            $sp_params = array_merge($sp_params, $params['TblCustomerMasterSearch']);

            if (empty($this->mcc_plant_code)) {
                $this->mcc_plant_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            }
            if (empty($this->bmc_code)) {
                $this->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
            }
            $sp_params['mcc_plant_code'] = $this->mcc_plant_code;
            $sp_params['bmc_code'] = $this->bmc_code;
            if ($this->validate()) {
                $output = \Yii::$app->general->getSpData($sp, $sp_params);
            }
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

    public function contactverificationsearch($params) {
        $this->load($params);
        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'datetime' => date('Y-m-d'),
                'master_type' => ''];
            $sp = 'portal_contact_data_verification';
            $sp_params = array_merge($sp_params, $params['TblCustomerMasterSearch']);
            if (empty($this->mcc_plant_code)) {
                $this->mcc_plant_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            }
            if (empty($this->bmc_code)) {
                $this->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
            }
            $sp_params['mcc_plant_code'] = $this->mcc_plant_code;
            $sp_params['bmc_code'] = $this->bmc_code;
            if ($this->validate()) {
                $output = \Yii::$app->general->getSpData($sp, $sp_params);
            }
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

}
