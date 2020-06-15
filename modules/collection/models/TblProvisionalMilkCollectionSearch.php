<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblProvisionalMilkCollection;

/**
 * TblProvisionalMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblProvisionalMilkCollection`.
 */
class TblProvisionalMilkCollectionSearch extends TblProvisionalMilkCollection {

    /**
     * @inheritdoc
     */
    public $union_code, $society_code;
    public $operator_fat, $operator_snf, $operator_qty, $operator_amount;
    public $from_date, $to_date, $from_shift, $to_shift, $sap_collection_type, $sap_data_post_status;

    public function rules() {
        return [
            [['provisional_milk_collection_code', 'sample_no', 'ack'], 'integer'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag', 'union_code', 'min_date', 'max_date', 'f_plant_code', 'f_mcc_code'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'milk_type_code', 'operator_fat', 'operator_snf', 'operator_qty', 'operator_amount', 'from_date', 'to_date', 'from_shift', 'to_shift', 'sap_collection_type', 'sap_data_post_status'], 'safe'],
            [['sap_collection_type'], 'required', 'on' => 'repostSapData'],
            [['protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'qty_mode', 'originating_org_type', 'originating_type'], 'safe'],
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
    public function searchCollection($params) {
        $query = TblProvisionalMilkCollection::find();
        $query->where(['tbl_provisional_milk_collection.member_code' => $params]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'memberCode', 'milkTypeCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        // echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }

}
