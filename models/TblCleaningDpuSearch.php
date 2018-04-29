<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblCleaningDpu;

/**
 * TblCleaningDpuSearch represents the model behind the search form about `app\models\TblCleaningDpu`.
 */
class TblCleaningDpuSearch extends TblCleaningDpu
{
    
    public $union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c1cycle', 'c1testing', 'c2cycle', 'c2testing', 'c3cycle', 'c3testing', 'c4cycle', 'c4testing', 'c5cycle', 'c5testing', 'Counter'/*,'CycleNo'*/], 'safe'],
            [['union_code','dcs_code', 'Dtdate', 'Shift', 'c1Date', 'c2Date', 'c3Date', 'c4Date', 'c5Date', 'CreatedDate', 'ModifyDate'], 'safe'],
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
        $query = TblCleaningDpu::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $this->load($params);
        //$query->andwhere(['tblCleaningDpu.dcs_code'=> $this->dcs_code]);
        $query->joinWith(['dcsCode']);
            Yii::$app->general->filterByOrg($query,$this,'tbl_dcs');
        if(!empty($request['min_date']) && !empty($request['max_date'])) 
        { 
            $start_date=date('Y-m-d',  strtotime($request['min_date']));
            $end_date=date('Y-m-d',  strtotime($request['max_date']));
            if($start_date!=$end_date)
                $query->andFilterWhere(['between', 'CAST(Dtdate AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), Dtdate, 126)', $start_date]);                
        }
        if($this->Shift!=3)
        {
            $query->andFilterWhere(['like', 'Shift', $this->Shift]);            
        }
        Yii::$app->general->filterByNumber($query, $this, ['c1cycle', 'c1testing', 'c2cycle', 'c2testing', 'c3cycle', 'c3testing', 'c4cycle', 'c4testing', 'c5cycle', 'c5testing']);
        if (!empty($this->Dtdate))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), Dtdate, 126)', date('Y-m-d', strtotime($this->Dtdate))]);
        if (!empty($this->c1Date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), c1Date, 126)', date('Y-m-d', strtotime($this->c1Date))]);
        if (!empty($this->c2Date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), c2Date, 126)', date('Y-m-d', strtotime($this->c2Date))]);
        if (!empty($this->c3Date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), c3Date, 126)', date('Y-m-d', strtotime($this->c3Date))]);
        if (!empty($this->c4Date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), c4Date, 126)', date('Y-m-d', strtotime($this->c4Date))]);
        if (!empty($this->c5Date))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), c5Date, 126)', date('Y-m-d', strtotime($this->c5Date))]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'Dtdate' => $this->Dtdate,
//            'c1Date' => $this->c1Date,
//            'c1cycle' => $this->c1cycle,
//            'c1testing' => $this->c1testing,
//            'c2Date' => $this->c2Date,
//            'c2cycle' => $this->c2cycle,
//            'c2testing' => $this->c2testing,
//            'c3Date' => $this->c3Date,
//            'c3cycle' => $this->c3cycle,
//            'c3testing' => $this->c3testing,
//            'c4Date' => $this->c4Date,
//            'c4cycle' => $this->c4cycle,
//            'c4testing' => $this->c4testing,
//            'c5Date' => $this->c5Date,
//            'c5cycle' => $this->c5cycle,
//            'c5testing' => $this->c5testing,
            'Counter' => $this->Counter,
            'CreatedDate' => $this->CreatedDate,
            'ModifyDate' => $this->ModifyDate,
        ]);

        return $dataProvider;
    }
}
