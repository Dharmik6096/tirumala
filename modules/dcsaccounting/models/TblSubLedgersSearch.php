<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblSubLedgers;

/**
 * TblSubLedgersSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblSubLedgers`.
 */
class TblSubLedgersSearch extends TblSubLedgers {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['sub_ledger_name', 'sub_ledger_code', 'reference_code', 'originating_org_code', 'originating_org_type', 'type', 'is_active', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_type', 'created_at', 'updated_at'], 'safe'],
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
        $query = TblSubLedgers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_sub_ledgers', 'tbl_sub_ledgers', 'tbl_sub_ledgers', 'tbl_sub_ledgers');


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_sub_ledgers.type' => $this->type,
            'tbl_sub_ledgers.is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'tbl_sub_ledgers.sub_ledger_code', $this->sub_ledger_code])
                ->andFilterWhere(['like', 'tbl_sub_ledgers.sub_ledger_name', $this->sub_ledger_name])
                ->andFilterWhere(['like', 'tbl_sub_ledgers.reference_code', $this->reference_code]);

        return $dataProvider;
    }

}
