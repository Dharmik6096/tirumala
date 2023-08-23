<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApplication;

/**
 * TblSchemeApplicationSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApplication`.
 */
class TblSchemeApplicationSearch extends TblSchemeApplication {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['application_id', 'scheme_id', 'originating_type', 'from_date', 'to_date'], 'safe'],
                [['customer_code', 'customer_type', 'application_date', 'remarks', 'application_status', 'status_date', 'status_by', 'status_remarks', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['min_pouring_day', 'min_pouring_qty', 'actual_pouring_day', 'actual_pouring_qty', 'scheme_value', 'approved_value'], 'number'],
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
    public function search($params, $pending_approval = FALSE) {
        $query = TblSchemeApplication::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['schemeId']);
        if ($pending_approval) {
            $levelQuery = TblSchemeApplicationApproval::find()
                    ->select(['application_id', 'MIN(level) AS level'])
                    ->where(['IS', 'application_status', NULL])
                    ->groupBy('application_id');

            $subQuery = TblSchemeApplicationApproval::find()
                    ->select(['tbl_scheme_application_approval.application_id', 'tbl_scheme_application_approval.app_approval_id'])
                    ->innerJoin(['lq' => $levelQuery], 'tbl_scheme_application_approval.application_id = lq.application_id AND tbl_scheme_application_approval.level = lq.level')
                    ->where(['IS', 'tbl_scheme_application_approval.application_status', NULL])
                    ->andWhere(['tbl_scheme_application_approval.user_code' => \Yii::$app->user->identity->user_code]);

            $query->innerJoin(['ap' => $subQuery], 'tbl_scheme_application.application_id = ap.application_id');
            $query->addSelect(['tbl_scheme_application.*', 'ap.app_approval_id as app_approval_id']);
            $this->application_status = ['registered', 'inprocess'];
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_scheme_application', 'tbl_scheme_application', 'tbl_scheme_application', 'tbl_scheme_application');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d', strtotime('-30 DAYS'));
        $query->andFilterWhere(['>=', 'tbl_scheme_application.application_date', $from_date]);


        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'tbl_scheme_application.application_date', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_scheme_application.application_id' => $this->application_id,
            'tbl_scheme_application.application_status' => $this->application_status,
        ]);

        $query->andFilterWhere(['like', 'tbl_scheme_application.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_scheme_application.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_scheme_master.scheme_name', $this->scheme_id]);

        return $dataProvider;
    }

}
