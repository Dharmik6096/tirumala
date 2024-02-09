<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblEiplAppFeedbackItem;

/**
 * TblEiplAppFeedbackItemSearch represents the model behind the search form about `app\modules\feedback\models\TblEiplAppFeedbackItem`.
 */
class TblEiplAppFeedbackItemSearch extends TblEiplAppFeedbackItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eipl_app_feedback_item_code'], 'integer'],
            [['feedback_item_name', 'created_at', 'created_by', 'updated_by'], 'safe'],
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
        $query = TblEiplAppFeedbackItem::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'eipl_app_feedback_item_code' => $this->eipl_app_feedback_item_code,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'feedback_item_name', $this->feedback_item_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
