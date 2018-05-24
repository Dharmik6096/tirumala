<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblDpuCalibration;

/**
 * TblDpuCalibrationSearch represents the model behind the search form about `app\models\TblDpuCalibration`.
 */
class TblDpuCalibrationSearch extends TblDpuCalibration
{
    
     public $union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['dcs_code', 'date', 'shift', 'milk_type_code', 'cycle','union_code'], 'safe'],
            [['fat', 'snf', 'water'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblDpuCalibration::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
            Yii::$app->general->filterByOrg($query,$this,'tbl_dcs');

        if(!empty($request['min_date']) && !empty($request['max_date'])) 
        { 
            $start_date=date('Y-m-d',  strtotime($request['min_date']));
            $end_date=date('Y-m-d',  strtotime($request['max_date']));
            if($start_date!=$end_date)
                $query->andFilterWhere(['between', 'CAST(tbl_dpu_calibration.date AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_dpu_calibration.date, 126)', $start_date]);                
        }
        if($this->shift!=3)
        {
            $query->andFilterWhere(['like', 'tbl_dpu_calibration.shift', $this->shift]);            
        }
        Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf']);
        Yii::$app->general->filterByNumber($query, $this, ['water']);
        if (!empty($this->date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_dpu_calibration.date, 126)', date('Y-m-d', strtotime($this->date))]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'fat' => $this->fat,
//            'snf' => $this->snf,
//            'water' => $this->water,
        ]);

        $query->andFilterWhere(['like', 'tbl_dpu_calibration.milk_type_code', $this->milk_type_code])
//            ->andFilterWhere(['like', 'shift', $this->shift])
            ->andFilterWhere(['like', 'tbl_dpu_calibration.cycle', $this->cycle]);
//echo $query->createCommand()->getRawSql();die;
        return $dataProvider;
    }
}
//