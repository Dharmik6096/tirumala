<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblGender;

/**
 * TblGenderSearch represents the model behind the search form about `app\modules\general\models\TblGender`.
 */
class TblGenderSearch extends TblGender
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender_code', 'is_active'], 'integer'],
            [['gender'], 'safe'],
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
        $query = TblGender::find();

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
            'gender_code' => $this->gender_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'gender', $this->gender]);

        return $dataProvider;
    }
}
