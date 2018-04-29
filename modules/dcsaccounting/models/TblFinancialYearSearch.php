<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblFinancialYear;

/**
 * TblFinancialYearSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblFinancialYear`.
 */
class TblFinancialYearSearch extends TblFinancialYear
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','code', 'created_at', 'created_by', 'deleted_at', 'deleted_by', 'ending_date', 'flg_sentbox_entry', 'starting_date', 'sync_status', 'sync_timestamp', 'updated_at', 'updated_by'], 'safe'],
            [['is_active', 'is_delete'], 'boolean'],
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
        $query = TblFinancialYear::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'is_active' => $this->is_active,
            'is_delete' => 0,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'starting_date',(!empty($this->starting_date))?date('Y-m-d', strtotime ($this->starting_date)):''])
            ->andFilterWhere(['like', 'ending_date',(!empty($this->ending_date))?date('Y-m-d', strtotime ($this->ending_date)):'']);

        return $dataProvider;
    }
}
