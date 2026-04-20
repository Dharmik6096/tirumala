<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblAccountPosting;

/**
 * TblAccountPostingSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblAccountPosting`.
 */
class TblAccountPostingSearch extends TblAccountPosting {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'account_posting_code', 'from_shift', 'to_shift', 'posting_type', 'status', 'event_type', 'originating_type', 'from_date', 'to_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblAccountPosting::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'fromShift', 'toShift', 'eventCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_account_posting', 'tbl_account_posting', 'tbl_account_posting', 'tbl_account_posting');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'from_date', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'to_date', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'tbl_account_posting.status' => $this->status,
            'tbl_account_posting.posting_type' => $this->posting_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_account_posting.account_posting_code', $this->account_posting_code])
                ->andFilterWhere(['like', 'tbl_event.event_name', $this->event_type]);

        return $dataProvider;
    }

}
