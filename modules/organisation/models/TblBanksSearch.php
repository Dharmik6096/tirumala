<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblBanks;

/**
 * TblBanksSearch represents the model behind the search form about `app\modules\organisation\models\TblBanks`.
 */
class TblBanksSearch extends TblBanks {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bank_code', 'bank_name', 'created_at', 'updated_at', 'created_by', 'updated_by', 'ac_no_length', 'originating_org_code', 'originating_org_type', 'originating_type', 'ledger_code'], 'safe'],
                [['is_active'], 'integer'],
                [['checked_ac_no', 'nationalized_bank', 'is_alpha_acno_allow'], 'boolean'],
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
        $query = TblBanks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['bank_name' => SORT_ASC]],
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByNumber($query, $this, ['ac_no_length']);
        // grid filtering conditions
        $query->andFilterWhere([
//            'ac_no_length' => $this->ac_no_length,
            'checked_ac_no' => $this->checked_ac_no,
            'nationalized_bank' => $this->nationalized_bank,
            'is_alpha_acno_allow' => $this->is_alpha_acno_allow,
            'tbl_banks.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_banks.bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'bank_name', $this->bank_name]);

        return $dataProvider;
    }

}
