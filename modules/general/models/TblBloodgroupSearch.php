<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblBloodgroup;

/**
 * TblBloodgroupSearch represents the model behind the search form about `app\modules\general\models\TblBloodgroup`.
 */
class TblBloodgroupSearch extends TblBloodgroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blood_group_code', 'is_active'], 'integer'],
            [['blood_group'], 'safe'],
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
        $query = TblBloodgroup::find();

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
            'blood_group_code' => $this->blood_group_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'blood_group', $this->blood_group]);

        return $dataProvider;
    }
}
