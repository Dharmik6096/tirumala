<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblTankerRateApplicability;
use yii\db\Query;

/**
 * TblTankerRateApplicabilitySearch represents the model behind the search form about `app\modules\tankermovement\models\TblTankerRateApplicability`.
 */
class TblTankerRateApplicabilitySearch extends TblTankerRateApplicability {

    public $code_ex;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_app_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'wef_date', 'tanker_rate_code', 'union_code', 'shift_code'], 'safe'],
            [['is_active'], 'boolean'],
            [['shift_code'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
        $query = TblTankerRateApplicability::find();
        $query->where(['tanker_rate_code' => $this->tanker_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['shiftCode']);
        $query->join('LEFT JOIN', 'tbl_party_master', 'tbl_party_master.party_master_code = tbl_tanker_rate_applicability.applicable_code');

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if ((!empty($this->wef_date))) {
            $wef_date = date('Y-m-d', strtotime($this->wef_date));
            $query->andFilterWhere(['like', 'CAST(wef_date AS DATE)', $wef_date]);
        }
        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        return $dataProvider;
    }

    public function RateList() {
        $query = TblTankerRateApplicability::find();
        $query->where(['applicable_code' => $this->applicable_code]);
        $query->andWhere(['is_active' => $this->is_active]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
