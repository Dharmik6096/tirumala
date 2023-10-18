<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblShiftTimeExceed;
use app\modules\general\models\TblProcessApproval;

/**
 * TblShiftTimeExceedSearch represents the model behind the search form about `app\modules\configuration\models\TblShiftTimeExceed`.
 */
class TblShiftTimeExceedSearch extends TblShiftTimeExceed {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['shift_time_exceed_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'org_type', 'org_code', 'date_time_of_collection', 'standard_time', 'exceed_time', 'remarks', 'status', 'status_datetime', 'status_by', 'status_remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'shift_code', 'originating_type'], 'safe'],
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
    public function search($params, $pending_approval = false) {
        $query = TblShiftTimeExceed::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['shift_time_exceed_code' => SORT_ASC]],
        ]);

        $this->load($params);

        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('amcs_shift_time_exceed');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_shift_time_exceed.shift_time_exceed_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['tbl_shift_time_exceed.*', 'ap.process_approval_code as process_approval_code']);
            $this->status = ['Register', 'Inprogress'];
            $query->where(['tbl_shift_time_exceed.status' => $this->status]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'cast(tbl_shift_time_exceed.date_time_of_collection as date)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_shift_time_exceed.shift_code' => $this->shift_code,
            'tbl_shift_time_exceed.status_datetime' => $this->status_datetime,
        ]);

        $query->andFilterWhere(['like', 'tbl_shift_time_exceed.shift_time_exceed_code', $this->shift_time_exceed_code])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.org_type', $this->org_type])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.org_code', $this->org_code])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.standard_time', $this->standard_time])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.exceed_time', $this->exceed_time])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.status_by', $this->status_by])
                ->andFilterWhere(['like', 'tbl_shift_time_exceed.status_remarks', $this->status_remarks]);

        return $dataProvider;
    }

}
