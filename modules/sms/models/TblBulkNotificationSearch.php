<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblBulkNotification;

/**
 * TblBulkNotificationSearch represents the model behind the search form about `app\modules\sms\models\TblBulkNotification`.
 */
class TblBulkNotificationSearch extends TblBulkNotification {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bulk_notification_id', 'content_id', 'status'], 'integer'],
                [['union_code', 'receiver_type', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'app_type', 'login_type', 'wef_date', 'title', 'message', 'campaign_name', 'created_at', 'created_by', 'entry_datetime', 'pickup_datetime', 'response_datetime', 'from_date', 'to_date'], 'safe'],
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
        $query = TblBulkNotification::find();

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

        $query->joinWith(['memberCode', 'apiMaster']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_bulk_notification', 'tbl_bulk_notification', 'tbl_bulk_notification');

        if (!empty($this->wef_date))
            $query->andFilterWhere(['and', ['>=', 'wef_date', date('Y-m-d', strtotime($this->wef_date)) . ' 00:00:00.000'], ['<=', 'wef_date', date('Y-m-d', strtotime($this->wef_date)) . ' 23:59:59.000']]);
        // grid filtering conditions


        if (!empty($this->from_date)) {
            $query->andFilterWhere(['>=', 'wef_date', date('Y-m-d', strtotime($this->from_date))]);
        }

        if (!empty($this->to_date)) {
            $query->andFilterWhere(['<=', 'wef_date', date('Y-m-d', strtotime($this->to_date))]);
        }
        $query->andFilterWhere([
            'receiver_type' => $this->receiver_type,
            'content_id' => $this->content_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_api_master.api_name', $this->app_type])
                ->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'message', $this->message])
                ->andFilterWhere(['like', 'campaign_name', $this->campaign_name]);

        return $dataProvider;
    }

}
