<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetail;
use app\modules\dcsaccounting\models\TblTaxDetail;

/**
 * TblLedgerMappingTaxDetailSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerMappingTaxDetail`.
 */
class TblLedgerMappingTaxDetailSearch extends TblLedgerMappingTaxDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['originating_type', 'ledger_mapping_tax_detail_code', 'ledger_code', 'tax_detail_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerMappingTaxDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_ledger_mapping_tax_detail', 'tbl_ledger_mapping_tax_detail', 'tbl_ledger_mapping_tax_detail', 'tbl_ledger_mapping_tax_detail');
        $query->joinWith(['ledgerCode', 'taxDetailCode', 'taxDetailCode.taxCode']);



        $query->andFilterWhere(['like', 'tbl_ledger_mapping_tax_detail.ledger_mapping_tax_detail_code', $this->ledger_mapping_tax_detail_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code])
                ->andFilterWhere(['like', 'tbl_tax.tax_name', $this->tax_detail_code]);

        return $dataProvider;
    }

    public function mappingSearch($params, $tax_code) {
        $query = TblTaxDetail::find()->alias('td')->select(['bt.basic_tax_name', 't.tax_name', 'td.tax_detail_code', 'td.percentage', 'td.union_code', 'lmtd.ledger_code'])
                ->innerJoin(['t' => 'tbl_tax'], 'td.tax_code = t.tax_code')
                ->innerJoin(['bt' => 'tbl_basic_tax'], 'bt.basic_tax_code = td.basic_tax_code')
                ->leftJoin(['lmtd' => 'tbl_ledger_mapping_tax_detail'], 'lmtd.tax_detail_code = td.tax_detail_code')
                ->where(['td.tax_code' => $tax_code, 'td.is_active' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['td.union_code' => explode(',', $unions)]);
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
