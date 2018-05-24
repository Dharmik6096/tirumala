<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;

/**
 * TblPurchaseRateApplicabilitySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblPurchaseRateApplicability`.
 */
class TblPurchaseRateApplicabilitySearch extends TblPurchaseRateApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_app_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'purchase_rate_code', 'union_code', 'shift_code', 'is_download', 'download_date_time'], 'safe'],
            [['is_active'], 'boolean'],
//            [['shift_code'], 'integer'],
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
        $query = TblPurchaseRateApplicability::find();
        $query->where(['purchase_rate_code' => $this->purchase_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'shiftCode']);
        //$query->joinWith(['rateType','dcsCode','rateMethod']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //Yii::$app->general->filterByOrg($query,$this);
        if ((!empty($this->wef_date))) {
            $wef_date = date('Y-m-d', strtotime($this->wef_date));
            $query->andFilterWhere(['like', 'CAST(wef_date AS DATE)', $wef_date]);
        }
        if (!empty($this->download_date_time))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), download_date_time, 126)', date('Y-m-d', strtotime($this->download_date_time))]);
        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
//            'wef_date' => $this->wef_date,
//            'shift_code' => $this->shift_code,
        ]);


        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'rate_app_code', $this->rate_app_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'is_download', $this->is_download]);
        //->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code])
        //->andFilterWhere(['like', 'union_code', $this->union_code]);
        //echo $query->createCommand()->rawSql;exit;
        return $dataProvider;
    }

    public function RateList() {
        $query = TblPurchaseRateApplicability::find();
        $query->where(['dcs_code' => $this->dcs_code]);
        $query->andWhere(['is_active' => $this->is_active]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

}
