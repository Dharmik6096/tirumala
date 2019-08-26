<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMACAlibrationChange;

/**
 * TblMACAlibrationChangeSearch represents the model behind the search form about `app\modules\collection\models\TblMACAlibrationChange`.
 */
class TblMACAlibrationChangeSearch extends TblMACAlibrationChange {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'ref_id'], 'integer'],
            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'MilkType', 'updatedby', 'updateddate', 'from_date', 'to_date'], 'safe'],
            [['CalibrationFat', 'CalibrationSnf', 'CalibrationWater'], 'number'],
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
        $query = TblMACAlibrationChange::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'bmcCode', 'milkTypeCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
//            $from_date .=' ';
            $query->andFilterWhere(['>=', 'cast(dtdate as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
//            $to_date .=' ';
            $query->andFilterWhere(['<=', 'cast(dtdate as date)', $to_date]);
        }
        if (!empty($this->dtdate))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', date('Y-m-d', strtotime($this->dtdate))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_MA_CAlibration_Change.id' => $this->id,
            'tbl_MA_CAlibration_Change.MilkType' => $this->MilkType
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->BMCCode])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->PPCode])
                ->andFilterWhere(['like', 'tbl_MA_CAlibration_Change.shift', $this->shift]);
//                ->andFilterWhere(['like', 'tbl_MA_CAlibration_Change.milkType', $this->milkType]);

        return $dataProvider;
    }

}
