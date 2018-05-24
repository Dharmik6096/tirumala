<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblShiftTime;

/**
 * TblShiftTimeSearch represents the model behind the search form about `app\modules\general\models\TblShiftTime`.
 */
class TblShiftTimeSearch extends TblShiftTime
{
    
    public $union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shift_time_code', 'shift_code', 'allow_after_collection', 'is_active','dcs_code', 'start_time', 'end_time', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by','union_code'], 'safe'],
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
        $query = TblShiftTime::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode']);
//        if(!empty($this->union_code))
//        {
//            $query->joinWith(['dcsCode']);
//            $query->andwhere(['tbl_dcs.union_code' => $this->union_code]);
//        }
        Yii::$app->general->filterByOrg($query,$this,'tbl_dcs');
//        if(!empty($this->shift))
//        {
//            $query->andFilterWhere(['like', 'shift_id', $this->shift_id]);            
//        }
        
        if($this->shift_code!=3)
        {
            $query->andFilterWhere(['like', 'tbl_shift_time.shift_code', $this->shift_code]);            
        }
        if(!empty($this->wef_date))
            $query->andwhere(['tbl_shift_time.wef_date' => date('Y-m-d', strtotime($this->wef_date))]);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_shift_time.shift_time_code' => $this->shift_time_code,
//            'shift_code' => $this->shift_code,
            'tbl_shift_time.start_time' => $this->start_time,
            'tbl_shift_time.end_time' => $this->end_time,
            'tbl_shift_time.allow_after_collection' => $this->allow_after_collection,
            'tbl_shift_time.is_active' => $this->is_active,
            'tbl_shift_time.dcs_code'=> $this->dcs_code
        ]);

        return $dataProvider;
    }
}
