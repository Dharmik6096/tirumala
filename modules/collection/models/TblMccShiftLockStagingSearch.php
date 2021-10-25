<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMccShiftLockStaging;

/**
 * TblMccShiftLockStagingSearch represents the model behind the search form about `app\modules\collection\models\TblMccShiftLockStaging`.
 */
class TblMccShiftLockStagingSearch extends TblMccShiftLockStaging {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staging_code', 'shift_lock_code', 'mcc_plant_code', 'shift_code', 'date_time_of_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime'], 'safe'],
            [['qty', 'avg_fat', 'avg_snf', 'amount'], 'number'],
            [['originating_type', 'data_post_status'], 'integer'],
            [['from_date', 'to_date', 'from_shift', 'to_shift'], 'safe']
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
        $query = TblMccShiftLockStaging::find();

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
        $query->joinWith(['lockData']);
        // grid filtering conditions
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_shift_lock', 'tbl_mcc_shift_lock', 'tbl_mcc_shift_lock');

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_mcc_shift_lock_staging.date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_mcc_shift_lock_staging.date_time_of_collection', $to_date]);
        }
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_mcc_shift_lock_staging.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query->andFilterWhere(['like', 'staging_code', $this->staging_code])
                ->andFilterWhere(['like', 'shift_lock_code', $this->shift_lock_code])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'avg_fat', $this->avg_fat])
                ->andFilterWhere(['like', 'avg_snf', $this->avg_snf])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'data_post_status', $this->data_post_status]);

        return $dataProvider;
    }

}
