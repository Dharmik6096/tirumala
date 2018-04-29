<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplaintActivity;

/**
 * TblComplainActivitySearch represents the model behind the search form about `app\modules\complain\models\TblComplainActivity`.
 */
class TblComplaintActivitySearch extends TblComplaintActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'complaint_code'], 'integer'],
            [['status', 'remarks', 'issue_type', 'contact_person', 'date', 'updated_at', 'updated_by','affects_data'], 'safe'],
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
        $query = TblComplaintActivity::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'complaint_code' => $this->complaint_code,
//            'date' => $this->date,
//            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'issue_type', $this->issue_type])
            ->andFilterWhere(['like', 'affects_data', $this->affects_data])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person]);
//            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
