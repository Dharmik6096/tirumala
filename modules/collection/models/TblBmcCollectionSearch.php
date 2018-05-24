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

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_code', 'milk_type_code', 'sample_no', 'ack'], 'integer'],
            [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'remarks'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
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
        $query->joinWith(['dcsCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'tbl_bmc_collection.date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)).' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)).' 23:59:59.000']]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bmc_collection.milk_collection_code' => $this->milk_collection_code,
            'tbl_bmc_collection.milk_type_code' => $this->milk_type_code,
            'tbl_bmc_collection.fat' => $this->fat,
            'tbl_bmc_collection.snf' => $this->snf,
            'tbl_bmc_collection.water' => $this->water,
            'tbl_bmc_collection.qty' => $this->qty,
            'tbl_bmc_collection.rtpl' => $this->rtpl,
            'tbl_bmc_collection.amount' => $this->amount,
            'tbl_bmc_collection.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_bmc_collection.sample_no' => $this->sample_no,
            'tbl_bmc_collection.ack' => $this->ack,
            'tbl_bmc_collection.dt_date' => $this->dt_date,
            'tbl_bmc_collection.sms_timestamp' => $this->sms_timestamp,
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc_collection.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.name', $this->name])
                ->andFilterWhere(['like', 'tbl_bmc_collection.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_bmc_collection.auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'tbl_bmc_collection.shift_code', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'tbl_bmc_collection.rate_code', $this->rate_code])
                ->andFilterWhere(['like', 'tbl_bmc_collection.error_log', $this->error_log])
                ->andFilterWhere(['like', 'tbl_bmc_collection.soc_bmc_flag', $this->soc_bmc_flag])
                ->andFilterWhere(['like', 'tbl_bmc_collection.sms_status', $this->sms_status])
                ->andFilterWhere(['like', 'tbl_bmc_collection.sms_msgid', $this->sms_msgid])
                ->andFilterWhere(['like', 'tbl_bmc_collection.sms_mobile', $this->sms_mobile])
                ->andFilterWhere(['like', 'tbl_bmc_collection.sms_errorlog', $this->sms_errorlog])
                ->andFilterWhere(['like', 'tbl_bmc_collection.remarks', $this->remarks]);

        return $dataProvider;
    }

}
