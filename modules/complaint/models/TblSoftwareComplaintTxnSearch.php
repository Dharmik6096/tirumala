<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblSoftwareComplaintTxn;

/**
 * TblSoftwareComplaintTxnSearch represents the model behind the search form about `app\modules\complaint\models\TblSoftwareComplaintTxn`.
 */
class TblSoftwareComplaintTxnSearch extends TblSoftwareComplaintTxn {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['complaint_txn_code', 'complaint_code', 'assign_to', 'assign_date', 'assign_time', 'assign_remarks', 'attachment', 'complaint_status', 'resolve_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['originating_type'], 'integer'],
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
        $query = TblSoftwareComplaintTxn::find();

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
            'complaint_code' => $this->complaint_code,
        ]);



        return $dataProvider;
    }

}
