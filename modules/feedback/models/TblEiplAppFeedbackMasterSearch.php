<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblEiplAppFeedbackMaster;

/**
 * TblEiplAppFeedbackMasterSearch represents the model behind the search form about `app\modules\feedback\models\TblEiplAppFeedbackMaster`.
 */
class TblEiplAppFeedbackMasterSearch extends TblEiplAppFeedbackMaster {

    public $from_date, $to_date, $union_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['eipl_app_feedback_master_code', 'feedback_status'], 'integer'],
                [['eipl_app_feedback_item_code', 'union_code', 'from_date', 'to_date', 'user_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'feedback_message', 'feedback_message_datetime', 'user_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblEiplAppFeedbackMaster::find();

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

        $query->joinWith(['plantCode', 'userCodeById', 'eiplAppFeedbackItemCode', 'mccCode', 'bmcCode', 'dcsCode']);

        Yii::$app->general->filterByOrg($query, $this, ['masterplant'], ['tbl_eipl_app_feedback_master', 'plant_code'], ['tbl_eipl_app_feedback_master', 'mcc_plant_code'], ['tbl_eipl_app_feedback_master', 'bmc_code'], ['tbl_eipl_app_feedback_master', 'dcs_code']);

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_eipl_app_feedback_master.feedback_message_datetime', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_eipl_app_feedback_master.feedback_message_datetime', $to_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_eipl_app_feedback_master.eipl_app_feedback_master_code' => $this->eipl_app_feedback_master_code,
            'tbl_eipl_app_feedback_master.feedback_status' => $this->feedback_status,
            'tbl_eipl_app_feedback_master.created_at' => $this->created_at,
            'tbl_eipl_app_feedback_master.updated_at' => $this->updated_at,
        ]);
        if (!empty($this->feedback_message_datetime))
            $query->andFilterWhere(['CAST(tbl_eipl_app_feedback_master.feedback_message_datetime as date)' => date('Y-m-d', strtotime($this->feedback_message_datetime))]);

        if(!empty($this->feedback_message)) {
        $query->andFilterWhere(['like', 'tbl_eipl_app_feedback_master.feedback_message', $this->feedback_message]);
        }
        $query->andFilterWhere(['like', 'user.name', $this->user_code]);
        $query->andFilterWhere(['like', 'tbl_eipl_app_feedback_item.feedback_item_name', $this->eipl_app_feedback_item_code]);
        $query->andFilterWhere(['like', 'tbl_eipl_app_feedback_master.plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'tbl_eipl_app_feedback_master.user_type', $this->user_type]);

        return $dataProvider;
    }

}
