<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblTaskType;

/**
 * TblTaskTypeSearch represents the model behind the search form about `app\modules\tms\models\TblTaskType`.
 */
class TblTaskTypeSearch extends TblTaskType
{
    public $f_union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_type_code', 'has_form', 'is_active', 'originating_type'], 'integer'],
            [['task_type', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'f_union_code'], 'safe'],
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
        $query = TblTaskType::find();

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

        Yii::$app->general->filterByOrg($query, $this);

        // grid filtering conditions
        $query->andFilterWhere([            
            'has_form' => $this->has_form,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'task_type', $this->task_type])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);
        return $dataProvider;
    }
}
