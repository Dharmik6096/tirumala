<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblWeightRejection;

/**
 * TblWeightRejectionSearch represents the model behind the search form about `app\modules\collection\models\TblWeightRejection`.
 */
class TblWeightRejectionSearch extends TblWeightRejection {

    public $from_date, $to_date, $from_shift, $to_shift, $ref_code, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sample_no', 'date_time_of_collection', 'shift_code', 'milk_type_code', 'qty_mode', 'qty', 'converted_qty_mode', 'converted_qty', 'cans', 'return_type', 'device_id', 'version_no', 'doc_no', 'vehicle_no', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'rejection_reason_code', 'rejection_responsibility_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date', 'from_shift', 'to_shift', 'ref_code', 'bmc_ref_code'], 'safe'],
            [['sample_no', 'milk_type_code', 'qty_mode', 'converted_qty_mode', 'return_type', 'doc_no', 'rejection_reason_code', 'rejection_responsibility_code'], 'integer'],
            [['qty', 'converted_qty', 'cans'], 'number'],
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
        $query = TblWeightRejection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'shiftCode', 'bmcCode', 'milkTypeCode', 'routeCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_rejection', 'tbl_weight_rejection', 'tbl_weight_rejection');

        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_rejection.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }

        $query->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code]);

        return $dataProvider;
    }

}
