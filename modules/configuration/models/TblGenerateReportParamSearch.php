<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblGenerateReportParam;

/**
 * TblGenerateReportParamSearch represents the model behind the search form about `app\modules\configuration\models\TblGenerateReportParam`.
 */
class TblGenerateReportParamSearch extends TblGenerateReportParam {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['report_param_code', 'data_post_status'], 'integer'],
            [['union_code', 'plant_code', 'mcc_code', 'dcs_code', 'bmc_code', 'from_date', 'to_date', 'report_key', 'file_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'resp_status', 'resp_desc', 'report_name', 'originating_type'], 'safe'],
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
        $query = TblGenerateReportParam::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userCode']);
        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_generate_report_param');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere(['tbl_generate_report_param.created_by' => Yii::$app->session->get('UserCode')]);
        // grid filtering conditions
        $query->andFilterWhere([
            'report_param_code' => $this->report_param_code,
            'data_post_status' => $this->data_post_status,
            'originating_type' => $this->originating_type,
        ]);
        if (!empty($this->from_date)) {
            $query->andwhere(['cast(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        }
        if (!empty($this->to_date)) {
            $query->andwhere(['cast(to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);
        }
        $query->andFilterWhere(['like', 'report_key', $this->report_key])
                ->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'report_name', $this->report_name])
                ->andFilterWhere(['like', 'resp_status', $this->resp_status])
                ->andFilterWhere(['like', 'resp_desc', $this->resp_desc]);

        return $dataProvider;
    }

}
