<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblFatSnfThreshold;

/**
 * TblFatSnfThresholdSearch represents the model behind the search form about `app\modules\general\models\TblFatSnfThreshold`.
 */
class TblFatSnfThresholdSearch extends TblFatSnfThreshold
{
    public $union_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minimum_fat', 'maximum_fat', 'minimum_snf', 'maximum_snf','threshold_code', 'shift_id', 'is_active','dcs_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by','union_code'], 'safe'],
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
        $query = TblFatSnfThreshold::find();

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
        
        Yii::$app->general->filterByNumber($query,$this,['minimum_fat','maximum_fat','minimum_snf','maximum_snf']);
        Yii::$app->general->filterByOrg($query,$this,'tbl_dcs');
            
//        if(!empty($this->shift))
//        {
//            $query->andFilterWhere(['like', 'shift_id', $this->shift_id]);            
//        }
        if($this->shift_id!=3)
        {
            $query->andFilterWhere(['like', 'tbl_fat_snf_threshold.shift_id', $this->shift_id]);            
        }
        if(!empty($this->wef_date))
            $query->andwhere(['tbl_fat_snf_threshold.wef_date' => date('Y-m-d', strtotime($this->wef_date))]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_fat_snf_threshold.threshold_code' => $this->threshold_code,
//            'minimum_fat' => $this->minimum_fat,
//            'maximum_fat' => $this->maximum_fat,
//            'minimum_snf' => $this->minimum_snf,
//            'maximum_snf' => $this->maximum_snf,
            'tbl_fat_snf_threshold.is_active' => $this->is_active,
            'tbl_fat_snf_threshold.dcs_code'=> $this->dcs_code
        ]);


        return $dataProvider;
    }
}
