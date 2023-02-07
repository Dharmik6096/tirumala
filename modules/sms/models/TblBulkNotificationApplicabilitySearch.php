<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblBulkNotificationApplicability;

/**
 * TblBulkNotificationApplicabilitySearch represents the model behind the search form about `app\modules\sms\models\TblBulkNotificationApplicability`.
 */
class TblBulkNotificationApplicabilitySearch extends TblBulkNotificationApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bulk_notification_app_code', 'bulk_notification_id', 'is_active', 'originating_type'], 'integer'],
                [['wef_date', 'applicable_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['entry_datetime', 'pickup_datetime', 'response_datetime', 'resp_desc'], 'safe'],
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
        $query = TblBulkNotificationApplicability::find();

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
            'bulk_notification_app_code' => $this->bulk_notification_app_code,
            'bulk_notification_id' => $this->bulk_notification_id,
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
