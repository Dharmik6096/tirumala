<?php

namespace app\modules\sms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

class TblAlertNotificationPortalSearch extends TblAlertNotificationPortal {

    public $from_date, $to_date;

    public function rules() {
        return [
            [['receiver_detail', 'receiver_type', 'message', 'header_info', 'status', 'send_status', 'refecence_code', 'module_type', 'entry_datetime', 'response_datetime', 'created_by', 'send_mail', 'mail_receiver_detail', 'refecence_code', 'module_type', 'report_param', 'report_path', 'from_date', 'to_date'], 'safe'],
            [['from_date', 'to_date'], 'validateToDate'],
        ];
    }

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
        $query = TblAlertNotificationPortal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userCode']);

        $this->load($params);

        $this->from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d', strtotime('-5 days'));
        $from_datetime = $this->from_date . ' 00:00:00';

        $this->to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_datetime = $this->to_date . ' 23:59:59';

        $query->andFilterWhere(['>=', 'tbl_alert_notification_portal.entry_datetime', $from_datetime]);
        $query->andFilterWhere(['<=', 'tbl_alert_notification_portal.entry_datetime', $to_datetime]);

        $query->andWhere(['receiver_detail' => Yii::$app->session->get('UserCode')]);

        $query->andWhere(['>=', 'send_mail', 1]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'send_status' => $this->send_status,
            'send_mail' => $this->send_mail,
        ]);

        $query->andFilterWhere(['like', 'user.name', $this->receiver_detail])
                ->andFilterWhere(['like', 'message', $this->message])
                ->andFilterWhere(['like', 'header_info', $this->header_info])
                ->andFilterWhere(['like', 'receiver_type', $this->receiver_type])
                ->andFilterWhere(['like', 'refecence_code', $this->refecence_code])
                ->andFilterWhere(['like', 'module_type', $this->module_type])
                ->andFilterWhere(['like', 'mail_receiver_detail', $this->mail_receiver_detail]);

        return $dataProvider;
    }

    public function validateToDate($attribute, $params) {
        $minDateStr = date('Y-m-d', strtotime('-5 days'));
        $maxDateStr = date('Y-m-d');

        $minTimestamp = strtotime($minDateStr);
        $maxTimestamp = strtotime($maxDateStr);

        if (!empty($this->$attribute)) {
            $selectedTimestamp = strtotime($this->$attribute);

            if ($selectedTimestamp < $minTimestamp || $selectedTimestamp > $maxTimestamp) {
                $attributeLabel = $this->getAttributeLabel($attribute);
                $this->addError($attribute, "$attributeLabel must be between $minDateStr and $maxDateStr.");
                return false;
            }
        }
        if ($attribute == 'to_date' && !empty($this->from_date) && !empty($this->to_date)) {
            if (strtotime($this->from_date) > strtotime($this->to_date)) {
                $this->addError('to_date', 'To Date must be greater than or equal to From Date.');
                return false;
            }
        }
    }

}
