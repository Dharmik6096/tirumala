<?php

namespace app\modules\syncutility\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\syncutility\models\TblForceSyncRequest;

/**
 * TblForceSyncRequestSearch represents the model behind the search form about `app\modules\syncutility\models\TblForceSyncRequest`.
 */
class TblForceSyncRequestSearch extends TblForceSyncRequest {

    public $ref_code;
    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['force_sync_request_code', 'from_shift', 'to_shift', 'is_download', 'originating_type', 'from_date', 'to_date'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_datetime', 'to_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'ref_code', 'table_name'], 'safe'],
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
        $query = TblForceSyncRequest::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['dcsCode', 'forceSyncTableList']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;


        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andWhere(
                [
                    'or',
                        ['between', 'from_datetime', $from_date, $to_date],
                        ['between', 'to_datetime', $from_date, $to_date]
                ]
        );



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'is_download' => $this->is_download,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_force_sync_table_list.table_desc', $this->table_name]);

        return $dataProvider;
    }

}
