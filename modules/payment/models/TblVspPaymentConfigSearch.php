<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPaymentConfig;

/**
 * TblVspPaymentConfigSearch represents the model behind the search form about `app\modules\payment\models\TblVspPaymentConfig`.
 */
class TblVspPaymentConfigSearch extends TblVspPaymentConfig {

    public $from_date, $to_date, $bmc_name, $dcs_name, $ex_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_payment_config_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'billing_based_on', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['from_date', 'to_date', 'bmc_name', 'ex_code', 'dcs_name'], 'safe']
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
        $query = TblVspPaymentConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vsp_payment_config', 'tbl_vsp_payment_config', 'tbl_vsp_payment_config');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vsp_payment_config_code' => $this->vsp_payment_config_code,
            'transaction_date' => $this->transaction_date,
        ]);

        $query->andFilterWhere(['like', 'tbl_vsp_payment_config.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'billing_based_on', $this->billing_based_on])
                ->andFilterWhere(['like', 'tbl_vsp_payment_config.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_name])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', $this->ex_code]);

        return $dataProvider;
    }

}
