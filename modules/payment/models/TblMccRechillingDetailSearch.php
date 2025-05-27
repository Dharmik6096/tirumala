<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMccRechillingDetail;
use yii\db\Expression;

/**
 * TblMccRechillingDetailSearch represents the model behind the search form about `app\modules\payment\models\TblMccRechillingDetail`.
 */
class TblMccRechillingDetailSearch extends TblMccRechillingDetail {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_rechilling_detail_code', 'chiller_info_code', 'shift_code', 'originating_type'], 'integer'],
            [['mcc_rechilling_detail_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'chiller_info_code', 'chilling_date', 'shift_code', 'qty', 'rate', 'amount', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblMccRechillingDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'tbl_mcc_rechilling_detail.chilling_date', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'tbl_mcc_rechilling_detail.chilling_date', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_rechilling_detail', 'tbl_mcc_rechilling_detail', 'tbl_mcc_rechilling_detail');

        // grid filtering conditions
        $query->andFilterWhere([
            'qty' => $this->qty,
            'rate' => $this->rate,
            'amount' => $this->amount,
        ]);

        return $dataProvider;
    }

}
