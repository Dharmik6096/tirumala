<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblRateRecalculation;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;

/**
 * TblRateRecalculationSearch represents the model behind the search form about `app\modules\vsp\models\TblRateRecalculation`.
 */
class TblRateRecalculationSearch extends TblRateRecalculation {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_recalculation_code', 'rate_code', 'from_shift', 'to_shift'], 'safe'],
            [['rate_type', 'from_date', 'to_date', 'dcs_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'mcc_plant_code', 'bmc_code', 'plant_code', 'recalc_for', 'customer_code', 'customer_type', 'module_type', 'recalc_type'], 'safe'],
            [['recalc_for', 'plant_code', 'union_code', 'bmc_code', 'plant_code', 'mcc_plant_code'], 'required', 'on' => 'recalculation_search'],
            [['recalc_type', 'plant_code', 'union_code', 'bmc_code', 'plant_code', 'mcc_plant_code', 'from_shift', 'to_shift', 'from_date', 'to_date'], 'required', 'on' => 'recalculation_dispatch'],
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
        $query = TblRateRecalculation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_rate_recalculation', 'tbl_rate_recalculation', 'tbl_rate_recalculation');
        // $this->attributes=$params['TblRateRecalculationSearch'];
        //echo '<pre>';
        // print_r($params); exit;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d', strtotime('-1 years'));
        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andWhere('((\'' . $from_date . '\'  between from_date and to_date) OR (\'' . $to_date . '\' between from_date  and to_date) OR (from_date between \'' . $from_date . '\' and  \'' . $to_date . '\') OR (to_date between \'' . $from_date . '\' and \'' . $to_date . '\'))');

//        $query->alias('t');
//        $subquery = "STUFF((SELECT distinct ', ' + tbl_dcs.[dcs_name]
//         FROM [tbl_rate_recalculation] p1 left join [tbl_dcs] on  tbl_dcs.dcs_code = p1.dcs_code
//          WHERE t.[from_date] = p1.[from_date]
//		 and t.[to_date] = p1.[to_date]
//		 and t.[recalc_for] = p1.[recalc_for]
//		 and t.[rate_code] = p1.[rate_code]
//		 and t.[from_shift] = p1.[from_shift]
//		 and t.[to_shift] = p1.[to_shift]
//            FOR XML PATH(''), TYPE
//            ).value('.', 'NVARCHAR(MAX)')
//        ,1,1,'')";
        $query->select(['from_date', 'to_date', 'recalc_for', 'recalc_type', 'rate_code', 'from_shift', 'to_shift', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'rate_type', 'module_type']);
        $query->andFilterWhere([
            'from_shift' => $this->from_shift,
            'to_shift' => $this->to_shift,
            'recalc_type' => $this->recalc_type,
            'rate_code' => $this->rate_code
        ]);
        $query->andFilterWhere(['like', 'rate_type', $this->rate_type])
                ->andFilterWhere(['like', 'recalc_for', $this->recalc_for])
                ->andFilterWhere(['like', 'module_type', $this->module_type])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'customer_code', $this->customer_code]);
        $query->groupBy(['from_date', 'to_date', 'recalc_for', 'rate_code', 'from_shift', 'to_shift', 'recalc_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'rate_type', 'module_type']);
        //echo $query->createCommand()->rawSql; exit;
        return $dataProvider;
    }

    public function searchDataRecalculation($params, $sp = 'sp_Portal_Data_Recalculation') {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'dcs_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => '',
                'recalc_for' => ''];
            $sp_params = array_merge($sp_params, $params['TblRateRecalculationSearch']);
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);
            if ($sp == 'sp_Portal_Data_Recalculation_Custom') {
                unset($sp_params['dcs_code']);
            }
            //var_dump($sp_params); exit;
            if (!empty($sp_params['recalc_for']))
                $output = \Yii::$app->general->getSpData($sp, $sp_params);
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);

        $query = TblRateRecalculation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        $query->joinWith(['dcsCode']);
//        $query->andWhere(['tbl_dcs.mcc_plant_code' => $this->mcc_plant_code]);

        Yii::$app->general->filterByOrg($query, $this);

        if (!empty($this->from_date))
            $query->andFilterWhere(['CAST(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['CAST(to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);

        $query->andFilterWhere([
            'bmc_code' => $this->bmc_code,
//            'from_date' => $this->from_date,
//            'to_date' => $this->to_date,
            'recalc_for' => $this->recalc_for,
            'rate_code' => $this->rate_code,
            'from_shift' => $this->from_shift,
            'to_shift' => $this->to_shift,
            'module_type' => $this->module_type,
        ]);

        return $dataProvider;
    }

    public function searchDataDispatch($params) {
        $this->load($params);
        $output = [];
        if (!empty($params) && $this->validate()) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'dcs_code' => '',
                'from_date' => '',
                'from_shift' => '',
                'to_date' => '',
                'to_shift' => '',
                'recalc_type' => ''];
            $sp_params = array_merge($sp_params, $params['TblRateRecalculationSearch']);
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift']);
            unset($sp_params['to_shift']);
            $output = \Yii::$app->general->getSpData('sp_Portal_Data_Recalculation_Dispatch', $sp_params);
            if (empty($output)) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Dispatch Data not available.']);
            }
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        return $dataProvider;
    }

}
