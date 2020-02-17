<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHeadApplicability;

/**
 * TblBillHeadApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadApplicability`.
 */
class TblBillHeadApplicabilitySearch extends TblBillHeadApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_applicabilty_code'], 'integer'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'bill_head_code', 'union_code'], 'safe'],
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
        $query = TblBillHeadApplicability::find();
        $query->where(['tbl_bill_head_applicability.bill_head_code' => $this->bill_head_code]);
        $query->orderBy(['tbl_bill_head_applicability.wef_date' => SORT_DESC]);

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
        $query->joinWith(['dcsCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bill_head_applicability.bill_head_applicabilty_code' => $this->bill_head_applicabilty_code,
            'tbl_bill_head_applicability.wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : '',
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bill_head_applicability.bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_bill_head_applicability.union_code', $this->union_code]);

        return $dataProvider;
    }

}
