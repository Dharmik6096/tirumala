<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;

/**
 * TblDcsPurchaseRateSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRate`.
 */
class TblDcsPurchaseRateSearch extends TblDcsPurchaseRate {

    public $federation_code;
    public $union_code;
    public $dcs_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['purchase_rate_code', 'wef_date', 'shift_id', 'created_at', 'originating_org_type', 'deleted_at', 'description', 'flg_sentbox_entry', 'shift_applicability', 'originating_org_code', 'rate_gen_method_code', 'sync_status', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'updated_by', 'federation_code', 'union_code', 'dcs_code', 'reference_code', 'ts_rate'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
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
        $query = TblDcsPurchaseRate::find();
        $query->joinWith(['unionCode', 'dcsCode', 'shiftApplicability', 'rateMethod']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $this->load($params);
        if (Yii::$app->session->get('Unions') !== '' && empty($this->union_code)) {
            $query->andFilterWhere(['tbl_dcs_purchase_rate.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else {
            $query->andFilterWhere(['tbl_dcs_purchase_rate.union_code' => $this->union_code]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_dcs_purchase_rate.is_active' => $this->is_active,
                // 'tbl_dcs_purchase_rate.originating_org_type' => $this->originating_org_type,
        ]);


        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'description', $this->description])
                // ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'tbl_rate_generate_method.method', $this->rate_gen_method_code])
                ->andFilterWhere(['like', 'tbl_dcs_purchase_rate.shift_id', $this->shift_id])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_applicability])
                ->andFilterWhere(['like', 'reference_code', $this->reference_code])
                ->andFilterWhere(['like', 'ts_rate', $this->ts_rate]);

        return $dataProvider;
    }

}
