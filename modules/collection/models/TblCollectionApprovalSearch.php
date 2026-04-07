<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblCollectionApproval;

/**
 * TblCollectionApprovalSearch represents the model behind the search form about `app\modules\collection\models\TblCollectionApproval`.
 */
class TblCollectionApprovalSearch extends TblCollectionApproval {

    public $from_date, $to_date;

    public function rules() {
        return [
                [['uuid', 'date', 'code', 'requested_by', 'approved_by', 'approve_date', 'allow_till_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'collection_type', 'from_date', 'to_date', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
                [['shift_code', 'collection_type', 'is_approve', 'valid_hours', 'originating_type'], 'integer'],
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
        $query = TblCollectionApproval::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userAndroidCode', 'userCode', 'dcsCode', 'bmcCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $unions = !empty($this->f_union_code) ? $this->f_union_code : (!empty(Yii::$app->session->get('Unions')) ? explode(',', Yii::$app->session->get('Unions')) : '');
        $plants = !empty($this->f_plant_code) ? $this->f_plant_code : (!empty(Yii::$app->session->get('Plant')) ? explode(',', Yii::$app->session->get('Plant')) : '');
        $mccs = !empty($this->f_mcc_code) ? $this->f_mcc_code : (!empty(Yii::$app->session->get('MCC')) ? explode(',', Yii::$app->session->get('MCC')) : '');
        $bmcs = !empty($this->f_bmc_code) ? $this->f_bmc_code : (!empty(Yii::$app->session->get('BMC')) ? explode(',', Yii::$app->session->get('BMC')) : '');
        $dcss = !empty($this->f_dcs_code) ? $this->f_dcs_code : (!empty(Yii::$app->session->get('Dcs')) ? explode(',', Yii::$app->session->get('Dcs')) : '');
    
        if (!empty($unions)) {
            $query->andFilterWhere(['or', ['tbl_bmc.union_code' => $unions, 'tbl_collection_approval.collection_type' => 2], ['tbl_dcs.union_code' => $unions, 'tbl_collection_approval.collection_type' => 1]]);
        }
        if (!empty($plants)) {
            $query->andFilterWhere(['or', ['tbl_bmc.plant_code' => $plants, 'tbl_collection_approval.collection_type' => 2], ['tbl_dcs.plant_code' => $plants, 'tbl_collection_approval.collection_type' => 1]]);
        }
        if (!empty($mccs)) {
            $query->andFilterWhere(['or', ['tbl_bmc.mcc_plant_code' => $mccs, 'tbl_collection_approval.collection_type' => 2], ['tbl_dcs.mcc_plant_code' => $mccs, 'tbl_collection_approval.collection_type' => 1]]);
        }
        if (!empty($bmcs)) {
            $query->andFilterWhere(['or', ['tbl_bmc.bmc_code' => $bmcs, 'tbl_collection_approval.collection_type' => 2], ['tbl_dcs.bmc_code' => $bmcs, 'tbl_collection_approval.collection_type' => 1]]);
        }
        if (!empty($dcss)) {
            $query->andFilterWhere(['tbl_dcs.dcs_code' => $dcss, 'tbl_collection_approval.collection_type' => 1]);
        }

        if (!empty($this->collection_type)) {
            $query->andFilterWhere(['collection_type' => (int) $this->collection_type]);
        }
        if (!empty($this->date)) {
            $query->andFilterWhere(['cast(tbl_collection_approval.date as date)' => date('Y-m-d', strtotime($this->date))]);
        }

        if (!empty($this->from_date)) {
            $query->andFilterWhere(['>=', 'cast(tbl_collection_approval.date as date)', date('Y-m-d', strtotime($this->from_date))]);
        }

        if (!empty($this->to_date)) {
            $query->andFilterWhere(['<=', 'cast(tbl_collection_approval.date as date)', date('Y-m-d', strtotime($this->to_date))]);
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_collection_approval.valid_hours' => $this->valid_hours,
            'tbl_collection_approval.is_approve' => $this->is_approve
        ]);

        $query->andFilterWhere(['like', 'tbl_user_android.name', $this->requested_by])
                ->andFilterWhere(['like', 'user.name', $this->approved_by]);


        return $dataProvider;
    }

}
