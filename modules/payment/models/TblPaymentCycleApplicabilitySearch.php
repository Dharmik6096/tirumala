<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPaymentCycleApplicability;

/**
 * TblPaymentCycleApplicabilitySearch represents the model behind the search form about `app\modules\payment\models\TblPaymentCycleApplicability`.
 */
class TblPaymentCycleApplicabilitySearch extends TblPaymentCycleApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_cycle_applicabilty_code', 'payment_cycle_code', 'data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member', 'originating_type'], 'integer'],
            [['from_date', 'to_date', 'applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'created_by', 'created_at', 'updated_at', 'updated_by', 'check_for', 'data_status', 'union_code'], 'safe'],
            [['from_date', 'to_date', 'union_code', 'check_for', 'data_status'], 'required', 'on' => ['lock-unlock-bulk']],
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
        $query = TblPaymentCycleApplicability::find()->alias('app');
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->innerJoin('tbl_bmc as bmc', 'bmc.bmc_code = app.applicable_code');
        $query->joinWith(['customerType']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($request['TblPaymentCycleApplicabilitySearch']['from_date'])) {
            $from_date = date('Y-m-d', strtotime($request['TblPaymentCycleApplicabilitySearch']['from_date']));
            $query->andFilterWhere(['like', 'CAST(from_date AS DATE)', $from_date]);
        }
        if (!empty($request['TblPaymentCycleApplicabilitySearch']['to_date'])) {
            $to_date = date('Y-m-d', strtotime($request['TblPaymentCycleApplicabilitySearch']['to_date']));
            $query->andFilterWhere(['like', 'CAST(to_date AS DATE)', $to_date]);
        }

        if (Yii::$app->session->get('BMC') !== '') {
            $query->andFilterWhere(['applicable_code' => explode(',', Yii::$app->session->get('BMC'))]);
        } else if (Yii::$app->session->get('MCC') !== '') {
            $query->andFilterWhere(['bmc.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        } else if (Yii::$app->session->get('Plant') !== '') {
            $query->andFilterWhere(['bmc.plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        } else if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['bmc.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_cycle_code' => $this->payment_cycle_code,
            'payment_cycle_applicabilty_code' => $this->payment_cycle_applicabilty_code,
        ]);

        if (!empty($this->from_date))
            $query->andFilterWhere(['like', 'from_date', date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['like', 'to_date', date('Y-m-d', strtotime($this->to_date))]);

        $query->andFilterWhere(['like', 'app.applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_type])
                ->andFilterWhere(['like', 'app.applicable_code', $this->applicable_code]);

        return $dataProvider;
    }

    public function datasearch($params) {
        $query = TblPaymentCycleApplicability::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

//        $query->joinWith(['customerType']);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (empty($this->from_date)) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'applicable_code' => $this->applicable_code,
            'union_code' => $this->union_code,
        ]);

        if (!empty($this->check_for)) {
            if ($this->check_for == 'data_lock_member' || $this->check_for == 'sync_lock_member') {
                $query->andFilterWhere([$this->check_for => $this->data_status, 'billing_lock_member' => 0, 'ISNULL(process_lock_member, 0)' => 0]);
            } else {
                $query->andFilterWhere([$this->check_for => $this->data_status, 'billing_lock_bmc' => 0, 'ISNULL(process_lock_bmc, 0)' => 0]);
            }
        }
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query->andFilterWhere(['or', ['between', 'CAST(from_date AS DATE)', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))],
                ['between', 'CAST(to_date AS DATE)', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))]]);
        }

        return $dataProvider;
    }
}
