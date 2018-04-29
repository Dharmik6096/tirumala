<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplaint;

/**
 * TblComplainSearch represents the model behind the search form about `app\modules\complain\models\TblComplain`.
 */
class TblComplaintSearch extends TblComplaint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['complaint_code'], 'integer'],
            [['complaint_code','union_code', 'dcs_code', 'date', 'remarks', 'complaint_type', 'contact_person', 'status', 'resolve_date', 'resolve_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by','affects_data','attachment'], 'safe'],
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
        $query = TblComplaint::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(!empty($this->date))
            $query->andwhere(['date' => date('Y-m-d', strtotime($this->date))]);
        
        if(!empty($this->complaint_code)){
            if(preg_match('/^[1-9][0-9]*$/', $this->complaint_code)){
                $query->andwhere(['complaint_code' => $this->complaint_code]);
            }else{
                $query->andFilterWhere(['like', 'complaint_code', $this->complaint_code]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
//            'complaint_code' => $this->complaint_code,
//            'date' => $this->date,
            'resolve_date' => $this->resolve_date,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'complaint_type', $this->complaint_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'affects_data', $this->affects_data])
            ->andFilterWhere(['like', 'attachment', $this->attachment])
            ->andFilterWhere(['like', 'resolve_remarks', $this->resolve_remarks]);
//            ->andFilterWhere(['like', 'created_by', $this->created_by])
//            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
