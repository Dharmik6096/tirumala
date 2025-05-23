<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblMemberBillHead;

/**
 * TblMemberBillHeadSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblMemberBillHead`.
 */
class TblMemberBillHeadSearch extends TblMemberBillHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_default_head', 'is_disburse_allowed', 'head_type', 'allow_adjustment', 'is_active', 'originating_type', 'bill_head_code', 'bill_head_name', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblMemberBillHead::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_bill_head', 'tbl_member_bill_head', 'tbl_member_bill_head', 'tbl_member_bill_head');


        // grid filtering conditions
        $query->andFilterWhere([
            'is_default_head' => $this->is_default_head,
            'is_disburse_allowed' => $this->is_disburse_allowed,
            'head_type' => $this->head_type,
            'allow_adjustment' => $this->allow_adjustment,
            'is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'bill_head_name', $this->bill_head_name]);

        return $dataProvider;
    }

}
