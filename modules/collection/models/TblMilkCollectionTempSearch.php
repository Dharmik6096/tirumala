<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollectionTemp;

/**
 * TblMilkCollectionTempSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollectionTemp`.
 */
class TblMilkCollectionTempSearch extends TblMilkCollectionTemp {

    public $union_code;
    public $operator_fat, $operator_snf, $operator_qty, $operator_amount;
    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack', 'data_post_status', 'qty_mode', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'is_approved'], 'integer'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'status', 'qlty_time', 'qty_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'route_code', 'bmc_code'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty'], 'number'],
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
        $this->load($params);
        $query = TblMilkCollectionTemp::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'memberCode', 'milkTypeCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .=' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .=' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->shift != 3) {
            $query->andFilterWhere(['like', 'shift', $this->shift]);
        }

        //  Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf', 'qty', 'amount']);
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_collection_temp.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_collection_temp.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_collection_temp.qty', $this->qty]);
        }
        if (!empty($this->amount)) {
            $query->andFilterWhere([$this->operator_amount, 'tbl_milk_collection_temp.amount', $this->amount]);
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_collection_temp.milk_collection_code' => $this->milk_collection_code,
            'tbl_milk_collection_temp.water' => $this->water,
            'tbl_milk_collection_temp.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_milk_collection_temp.sample_no' => $this->sample_no,
            'tbl_milk_collection_temp.ack' => $this->ack,
        ]);

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.name', $this->name])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.rate_code', $this->rate_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.error_log', $this->error_log])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_temp.soc_bmc_flag', $this->soc_bmc_flag]);

//        echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }

    public function searchDcsMilkColl($params) {
        $this->load($params);
        $query = TblMilkCollectionTemp::find();
        $request = Yii::$app->request->queryParams;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->where(['dcs_code' => $this->dcs_code]);
        return $dataProvider;
    }

}
