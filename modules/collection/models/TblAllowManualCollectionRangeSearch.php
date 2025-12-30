<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblAllowManualCollectionRange;
use app\modules\general\models\TblProcessApproval;

/**
 * TblAllowManualCollectionRangeSearch represents the model behind the search form about `app\modules\collection\models\TblAllowManualCollectionRange`.
 */
class TblAllowManualCollectionRangeSearch extends TblAllowManualCollectionRange {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['allow_manual_collection_code', 'is_weight_manual', 'is_quality_manual', 'is_approved', 'complain_status', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'remark', 'entry_type', 'table_name', 'application_type', 'approved_at', 'approved_by', 'approval_status', 'complain_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'action_perform'], 'safe'],
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
    public function search($params, $pending_approval = false, $date_search = false, $complainInfo = false) {

        $query = TblAllowManualCollectionRange::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $this->load($params);
        if($complainInfo){
            $query->joinWith(['complainCode']);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('tbl_allow_manual_collection_range');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_allow_manual_collection_range.allow_manual_collection_code) = convert(varchar(max),ap.process_code)')
                    ->addSelect(['tbl_allow_manual_collection_range.*', 'ap.process_approval_code as process_approval_code']);

            $query->andWhere([
                'tbl_allow_manual_collection_range.approval_status' => ['Pending', 'Inprogress']
            ]);
        }

        // grid filtering conditions
        if ($date_search) {
            $query->andFilterWhere([
                'tbl_allow_manual_collection_range.union_code' => $this->union_code,
                'tbl_allow_manual_collection_range.plant_code' => $this->plant_code,
                'tbl_allow_manual_collection_range.mcc_plant_code' => $this->mcc_plant_code,
                'tbl_allow_manual_collection_range.bmc_code' => $this->bmc_code
            ]);
            if (!empty($this->from_date)) {
                $from_date = date('Y-m-d', strtotime($this->from_date));
            }
            if (!empty($this->to_date)) {
                $to_date = date('Y-m-d', strtotime($this->to_date));
            }
        } else {
            Yii::$app->general->filterByOrg($query, $this, 'tbl_allow_manual_collection_range', 'tbl_allow_manual_collection_range', 'tbl_allow_manual_collection_range', 'tbl_allow_manual_collection_range');

            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        }
        if (isset($from_date) && isset($to_date)) {
            $query->andFilterWhere(['>=', 'cast(tbl_allow_manual_collection_range.from_date as date)', $from_date]);
            $query->andFilterWhere(['<=', 'cast(tbl_allow_manual_collection_range.to_date as date)', $to_date]);
        }
        $query->andFilterWhere([
            'tbl_allow_manual_collection_range.is_quality_manual' => $this->is_quality_manual,
            'tbl_allow_manual_collection_range.is_weight_manual' => $this->is_weight_manual,
            'tbl_allow_manual_collection_range.is_approved' => $this->is_approved,
            'tbl_allow_manual_collection_range.table_name' => $this->table_name,
        ]);

        $query->andFilterWhere(['like', 'tbl_allow_manual_collection_range.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['like', 'entry_type', $this->entry_type])
                ->andFilterWhere(['like', 'application_type', $this->application_type])
                ->andFilterWhere(['like', 'approved_by', $this->approved_by])
                ->andFilterWhere(['like', 'approval_status', $this->approval_status])
                ->andFilterWhere(['like', 'complain_type', $this->complain_type])
                ->andFilterWhere(['like', 'action_perform', $this->action_perform]);

        return $dataProvider;
    }

}
