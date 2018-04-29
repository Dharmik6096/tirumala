<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblQualification;

/**
 * TblQualificationSearch represents the model behind the search form about `app\modules\general\models\TblQualification`.
 */
class TblQualificationSearch extends TblQualification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qualification_code', 'is_active','sequences_no'], 'integer'],
            [['created_at', 'created_by', 'qualification_name', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblQualification::find();

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
            'qualification_code' => $this->qualification_code,
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
            'sequences_no' => $this->sequences_no,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'qualification_name', $this->qualification_name])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
