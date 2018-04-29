<?php

namespace app\modules\rmrd\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\rmrd\models\Log;

/**
 * LogSearch represents the model behind the search form about `app\modules\rmrd\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id'], 'integer'],
            [['Date', 'Thread', 'Level', 'Logger', 'Message', 'Exception'], 'safe'],
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
        $query = Log::find();

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
        if(!empty($this->Date))
            $query->andwhere(['Date' => date('Y-m-d', strtotime($this->Date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'Id' => $this->Id,
//            'Date' => $this->Date,
        ]);

        $query->andFilterWhere(['like', 'Thread', $this->Thread])
            ->andFilterWhere(['like', 'Level', $this->Level])
            ->andFilterWhere(['like', 'Logger', $this->Logger])
            ->andFilterWhere(['like', 'Message', $this->Message])
            ->andFilterWhere(['like', 'Exception', $this->Exception]);

        return $dataProvider;
    }
}