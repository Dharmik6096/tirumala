<?php

namespace app\modules\insurance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\insurance\models\TblInsuranceMaster;

/**
 * TblInsuranceMasterSearch represents the model behind the search form about `app\modules\insurance\models\TblInsuranceMaster`.
 */
class TblInsuranceMasterSearch extends TblInsuranceMaster {

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['insurance_master_code', 'member_min_age', 'member_max_age', 'is_active', 'originating_type'], 'integer'],
            [['union_code', 'insurance_start_date', 'insurance_end_date', 'dcs_edit_start_date', 'dcs_edit_end_date', 'insurance_final_date', 'insurance_description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblInsuranceMaster::find();

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

//        $currentDate = date('Y-m-d');
//        $query->where(':date BETWEEN insurance_start_date AND insurance_end_date', [':date' => $currentDate]);

        if(!empty($this->insurance_master_code)){
            $query->andWhere(['insurance_master_code' => date('Y-m-d',strtotime($this->insurance_master_code))]);
        }
        if(!empty($this->insurance_start_date)){
            $query->andWhere(['insurance_start_date' => date('Y-m-d',strtotime($this->insurance_start_date))]);
        }
        if(!empty($this->insurance_end_date)){
            $query->andWhere(['insurance_end_date' => date('Y-m-d',strtotime($this->insurance_end_date))]);
        }
        if(!empty($this->dcs_edit_start_date)){
            $query->andWhere(['dcs_edit_start_date' => date('Y-m-d',strtotime($this->dcs_edit_start_date))]);
        }
        if(!empty($this->dcs_edit_end_date)){
            $query->andWhere(['dcs_edit_end_date' => date('Y-m-d',strtotime($this->dcs_edit_end_date))]);
        }
        if(!empty($this->insurance_final_date)){
            $query->andWhere(['insurance_final_date' => date('Y-m-d',strtotime($this->insurance_final_date))]);
        }

        $query->andFilterWhere(['like', 'insurance_description', $this->insurance_description])
            ->andFilterWhere(['like', 'member_min_age', $this->member_min_age])
            ->andFilterWhere(['like', 'member_max_age', $this->member_max_age]);

        return $dataProvider;
    }
}
