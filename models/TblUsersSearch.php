<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblUsers;

/**
 * TblUsersSearch represents the model behind the search form about `app\models\TblUsers`.
 */
class TblUsersSearch extends TblUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_code', 'create_at', 'delete_at', 'name', 'password', 'update_at', 'user_id', 'user_name'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
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
        $query = TblUsers::find();

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
            'is_active' => $this->is_active,
            'create_at' => $this->create_at,
            'is_delete' => $this->is_delete,
            'delete_at' => $this->delete_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'user_code', $this->user_code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
