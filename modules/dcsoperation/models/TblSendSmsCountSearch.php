<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblSendSmsCount;

/**
 * TblSendSmsCountSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblSendSmsCount`.
 */
class TblSendSmsCountSearch extends TblSendSmsCount {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['send_sms_count_code', 'count', 'from_device', 'shift_code', 'originating_type'], 'integer'],
                [['type', 'date_time_of_send', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblSendSmsCount::find();

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

        // grid filtering conditions

        Yii::$app->general->filterByOrg($query, $this, 'tbl_send_sms_count', 'tbl_send_sms_count', 'tbl_send_sms_count');

        if (!empty($this->date_time_of_send)) {
            $query->andFilterWhere(['and', ['>=', 'date_time_of_send', date('Y-m-d', strtotime($this->date_time_of_send)) . ' 00:00:00.000'], ['<=', 'date_time_of_send', date('Y-m-d', strtotime($this->date_time_of_send)) . ' 23:59:59.000']]);
        }
        $query->andFilterWhere(['like', 'type', $this->type])
                ->andFilterWhere(['like', 'count', $this->count])
                ->andFilterWhere(['like', 'from_device', $this->from_device]);

        return $dataProvider;
    }

}
