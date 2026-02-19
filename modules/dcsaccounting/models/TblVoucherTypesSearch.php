<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblVoucherTypes;

/**
 * TblVoucherTypesSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblVoucherTypes`.
 */
class TblVoucherTypesSearch extends TblVoucherTypes {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['voucher_type_name', 'voucher_type_code', 'is_active', 'originating_type', 'local_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'created_at', 'updated_at', 'ledger_code', 'voucher_type', 'credit_debit'], 'safe'],
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
        $query = TblVoucherTypes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'ledgerCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_voucher_types.voucher_type_code' => $this->voucher_type_code,
            'tbl_voucher_types.is_active' => $this->is_active,
            'tbl_voucher_types.voucher_type' => $this->voucher_type,
            'tbl_voucher_types.credit_debit' => $this->credit_debit
        ]);

        $query->andFilterWhere(['like', 'tbl_voucher_types.voucher_type_name', $this->voucher_type_name])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code]);


        return $dataProvider;
    }

}
