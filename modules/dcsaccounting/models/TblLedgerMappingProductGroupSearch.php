<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerMappingProductGroup;
use app\modules\product\models\TblProductGroup;

/**
 * TblLedgerMappingProductGroupSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerMappingProductGroup`.
 */
class TblLedgerMappingProductGroupSearch extends TblLedgerMappingProductGroup {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_group_code', 'originating_type', 'ledger_mapping_product_group_code', 'ledger_sale_code', 'ledger_purchase_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerMappingProductGroup::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        Yii::$app->general->filterByOrg($query, $this, 'tbl_ledger_mapping_product_group', 'tbl_ledger_mapping_product_group', 'tbl_ledger_mapping_product_group', 'tbl_ledger_mapping_product_group');
        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['tbl_ledger_mapping_product_group.union_code' => explode(',', $unions)]);
        }

        $query->joinWith(['ledgerPurchaseCode', 'ledgerSaleCode', 'productGroupCode']);


        $query->andFilterWhere(['like', 'tbl_ledger_mapping_product_group.ledger_mapping_product_group_code', $this->ledger_mapping_product_group_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_sale_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_purchase_code])
                ->andFilterWhere(['like', 'tbl_product_group.product_group_name', $this->product_group_code]);

        return $dataProvider;
    }

    public function mappingSearch($params) {
        $query = TblProductGroup::find()
                ->select(['tbl_product_group.union_code', 'tbl_product_group.product_group_code', 'tbl_product_group.product_group_name', 'tbl_ledger_mapping_product_group.ledger_sale_code', 'tbl_ledger_mapping_product_group.ledger_purchase_code', 'tbl_ledger_mapping_product_group.ledger_mapping_product_group_code'])
                ->leftJoin('tbl_ledger_mapping_product_group', 'tbl_ledger_mapping_product_group.product_group_code = tbl_product_group.product_group_code');

        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['tbl_product_group.union_code' => explode(',', $unions)]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
    }

}
