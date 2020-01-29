<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblBulkNotification;

/**
 * TblBulkNotificationSearch represents the model behind the search form about `app\modules\sms\models\TblBulkNotification`.
 */
class TblBulkNotificationSearch extends TblBulkNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulk_notification_id', 'receiver_type', 'content_id', 'status'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'app_type', 'login_type', 'wef_date', 'title', 'message', 'campaign_name', 'created_at', 'created_by', 'entry_datetime', 'pickup_datetime', 'response_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
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
        $query->andFilterWhere([
            'bulk_notification_id' => $this->bulk_notification_id,
            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'receiver_type' => $this->receiver_type,
            'content_id' => $this->content_id,
            'status' => $this->status,
            'entry_datetime' => $this->entry_datetime,
            'pickup_datetime' => $this->pickup_datetime,
            'response_datetime' => $this->response_datetime,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'app_type', $this->app_type])
            ->andFilterWhere(['like', 'login_type', $this->login_type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'campaign_name', $this->campaign_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
