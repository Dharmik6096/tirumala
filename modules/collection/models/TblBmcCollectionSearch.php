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
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)).' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)).' 23:59:59.000']]);
        // grid filtering conditions
        $query->andFilterWhere([
            'milk_collection_code' => $this->milk_collection_code,
            'milk_type_code' => $this->milk_type_code,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'water' => $this->water,
            'qty' => $this->qty,
            'rtpl' => $this->rtpl,
            'amount' => $this->amount,
            'date_time_of_recieve' => $this->date_time_of_recieve,
            'sample_no' => $this->sample_no,
            'ack' => $this->ack,
            'dt_date' => $this->dt_date,
            'sms_timestamp' => $this->sms_timestamp,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'shift_code', $this->shift_code])
                ->andFilterWhere(['like', 'village_code', $this->village_code])
                ->andFilterWhere(['like', 'type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'rate_code', $this->rate_code])
                ->andFilterWhere(['like', 'error_log', $this->error_log])
                ->andFilterWhere(['like', 'soc_bmc_flag', $this->soc_bmc_flag])
                ->andFilterWhere(['like', 'sms_status', $this->sms_status])
                ->andFilterWhere(['like', 'sms_msgid', $this->sms_msgid])
                ->andFilterWhere(['like', 'sms_mobile', $this->sms_mobile])
                ->andFilterWhere(['like', 'sms_errorlog', $this->sms_errorlog])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
