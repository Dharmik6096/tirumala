<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcCollection;

/**
 * TblBmcCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblBmcCollection`.
 */
class TblBmcCollectionSearch extends TblBmcCollection {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack'], 'integer'],
            [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'remarks', 'from_date', 'to_date', 'from_shift', 'to_shift', 'qty_mode', 'doc_no'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
            [['mcc_plant_code', 'plant_code', 'union', 'customer_code', 'customer_type', 'customer_name', 'bmc_code'], 'safe']
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
        $query = TblBmcCollection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'mainBmcCode']);

        Yii::$app->general->filterByOrg($query, $this);

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'tbl_bmc_collection.date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 23:59:59.000']]);
        // grid filtering conditions

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        $query->andFilterWhere([
            'tbl_bmc_collection.qty_mode' => $this->qty_mode,
            'tbl_bmc_collection.doc_no' => $this->doc_no,
        ]);
        $query->andFilterWhere(['like', 'tbl_bmc_collection.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.rtpl', $this->rtpl])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_bmc_collection.customer_code', $this->customer_code]);
        $query->orderBy(['tbl_bmc_collection.date_time_of_collection' => SORT_DESC, 'tbl_bmc.bmc_name' => SORT_ASC, 'tbl_bmc_collection.doc_no' => SORT_ASC, 'tbl_bmc_collection.sample_no' => SORT_ASC]);
        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);

        $query = TblBmcCollection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        $query->joinWith(['dcsCode']);
//        $query->andWhere(['tbl_dcs.mcc_plant_code' => $this->mcc_plant_code]);

        Yii::$app->general->filterByOrg($query, $this);

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);
        $query->andFilterWhere(['shift_code' => $this->shift_code]);
//        $query->andFilterWhere(['like', 'tbl_bmc_collection.dcs_code', $this->dcs_code]);
        $query->andFilterWhere(['like', 'tbl_bmc_collection.bmc_code', $this->bmc_code]);

        return $dataProvider;
    }

}
