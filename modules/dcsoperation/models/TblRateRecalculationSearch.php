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

    public $bmc_code, $mcc_code, $plant_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_recalculation_code', 'rate_code', 'from_shift', 'to_shift'], 'integer'],
            [['rate_type', 'from_date', 'to_date', 'dcs_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'mcc_code', 'bmc_code', 'plant_code', 'recalc_for'], 'safe'],
            [['recalc_for', 'mcc_code', 'plant_code', 'union_code', 'bmc_code'], 'required', 'on' => 'recalculation_search']
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
        Yii::$app->general->filterByOrg($query, $this);
        // $this->attributes=$params['TblRateRecalculationSearch'];
        //echo '<pre>';
        // print_r($params); exit;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), from_date, 126)', date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), to_date, 126)', date('Y-m-d', strtotime($this->to_date))]);


        $query->alias('t');
        $subquery = "STUFF((SELECT distinct ', ' + tbl_dcs.[dcs_name]
         FROM [tbl_rate_recalculation] p1 left join [tbl_dcs] on  tbl_dcs.dcs_code = p1.dcs_code
          WHERE t.[from_date] = p1.[from_date]
		 and t.[to_date] = p1.[to_date]
		 and t.[recalc_for] = p1.[recalc_for]
		 and t.[rate_code] = p1.[rate_code]
		 and t.[from_shift] = p1.[from_shift]
		 and t.[to_shift] = p1.[to_shift]
            FOR XML PATH(''), TYPE
            ).value('.', 'NVARCHAR(MAX)')
        ,1,1,'')";
        $query->select(['from_date', 'to_date', 'recalc_for', 'recalc_type', 'rate_code', 'from_shift', 'to_shift', $subquery . ' as dcs_code']);


        $query->andFilterWhere(['like', 'rate_type', $this->rate_type])
                ->andFilterWhere(['rate_code' => $this->rate_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);
        $query->groupBy(['from_date', 'to_date', 'recalc_for', 'rate_code', 'from_shift', 'to_shift', 'recalc_type']);
        //echo $query->createCommand()->rawSql; exit;
        return $dataProvider;
    }

    public function searchDataRecalculation($params, $sp = 'sp_Portal_Data_Recalculation') {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = ['union_code' => '',
                'plant_code' => '',
                'mcc_code' => '',
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

}
