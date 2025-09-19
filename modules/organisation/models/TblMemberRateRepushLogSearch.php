<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblMemberRateRepushLog;

/**
 * TblMemberRateRepushLogSearch represents the model behind the search form about `app\modules\organisation\models\TblMemberRateRepushLog`.
 */
class TblMemberRateRepushLogSearch extends TblMemberRateRepushLog {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'file_type', 'dpu_type', 'member_rate_repush_log_id', 'log_status', 'originating_type', 'purchase_rate_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dpu_type'], 'required', 'on' => ['repush']],
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
        $query = TblMemberRateRepushLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_rate_repush_log', 'tbl_member_rate_repush_log', 'tbl_member_rate_repush_log', 'tbl_member_rate_repush_log');

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_member_rate_repush_log.log_status' => $this->log_status,
            'tbl_member_rate_repush_log.file_type' => $this->file_type,
            'tbl_member_rate_repush_log.dpu_type' => $this->dpu_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_member_rate_repush_log.purchase_rate_code', $this->purchase_rate_code]);

        return $dataProvider;
    }

    public function searchLogData($params) {
        $query = TblDcs::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs', 'tbl_dcs', 'tbl_dcs','tbl_dcs');

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['tbl_dcs.bmc_code' => $this->bmc_code]);
        $query->andWhere(['tbl_dcs.dpu_type' => $this->dpu_type]);
        $query->andWhere('tbl_dcs.bmc_code is not null');

        return $dataProvider;
    }

}
