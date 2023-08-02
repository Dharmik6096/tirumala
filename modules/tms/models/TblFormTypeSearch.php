<?php

namespace app\modules\tms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tms\models\TblFormType;

/**
 * TblFormTypeSearch represents the model behind the search form about `app\modules\tms\models\TblFormType`.
 */
class TblFormTypeSearch extends TblFormType
{
    public $f_union_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_type_code', 'task_type_code', 'is_active', 'originating_type'], 'safe'],
            [['form_name', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'f_union_code'], 'safe'],
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
        $query = TblFormType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['taskType']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this);
        
        // grid filtering conditions
        $query->andFilterWhere([            
            'tbl_form_type.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'form_name', $this->form_name])
            ->andFilterWhere(['like', 'tbl_task_type.task_type', $this->task_type_code])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
