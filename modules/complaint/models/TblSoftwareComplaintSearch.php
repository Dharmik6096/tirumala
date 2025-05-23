<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblSoftwareComplaint;

/**
 * TblSoftwareComplaintSearch represents the model behind the search form about `app\modules\complaint\models\TblSoftwareComplaint`.
 */
class TblSoftwareComplaintSearch extends TblSoftwareComplaint {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complaint_code', 'union_code', 'dcs_code', 'contact_person', 'contact_person_no', 'complaint_date', 'product_code', 'product_name', 'service_call_no', 'complaint_type', 'priority', 'complaint_desc', 'remarks', 'assign_to', 'assign_date', 'assign_time', 'resolution_type', 'resolve_date', 'complaint_status', 'attachment', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['is_chargeable', 'originating_type'], 'integer'],
                [['amount'], 'number'],
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

        $query = TblSoftwareComplaint::find();

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
        $query->join('LEFT JOIN', 'tbl_user_engineer_mapping um', 'um.engineer_id = tbl_software_complaint.assign_to');

        $query->join('LEFT JOIN', 'tbl_user_engineer_mapping umc', 'umc.engineer_id = tbl_software_complaint.created_by');

        $query->andWhere(['or', ['tbl_software_complaint.created_by' => Yii::$app->session->get('UserCode')], ['tbl_software_complaint.assign_to' => Yii::$app->session->get('UserCode')], ['um.user_id' => Yii::$app->session->get('UserCode')], ['umc.user_id' => Yii::$app->session->get('UserCode')]]);

        if (!empty($this->complaint_date)) {
            $query->andwhere(['complaint_date' => date('Y-m-d', strtotime($this->complaint_date))]);
        }
        if (!empty($this->dcs_code)) {
            $query->andFilterWhere(['dcs_code' => $this->dcs_code,
            ]);
        }
        if (!empty($this->assign_date)) {
            $query->andwhere(['assign_date' => date('Y-m-d', strtotime($this->assign_date))]);
        }
        if (!empty($this->resolve_date)) {
            $query->andwhere(['resolve_date' => date('Y-m-d', strtotime($this->resolve_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'is_chargeable' => $this->is_chargeable,
            'complaint_type' => $this->complaint_type,
            'priority' => $this->priority,
            'resolution_type' => $this->resolution_type,
            'complaint_status' => $this->complaint_status,
        ]);

        $query->andFilterWhere(['like', 'complaint_code', $this->complaint_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'contact_person_no', $this->contact_person_no])
                ->andFilterWhere(['like', 'product_code', $this->product_code])
                ->andFilterWhere(['like', 'product_name', $this->product_name])
                ->andFilterWhere(['like', 'service_call_no', $this->service_call_no])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'complaint_desc', $this->complaint_desc])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'assign_time', $this->assign_time])
                ->andFilterWhere(['like', 'attachment', $this->attachment])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
