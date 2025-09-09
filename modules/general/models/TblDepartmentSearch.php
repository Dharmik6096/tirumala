<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblDepartment;

/**
 * TblDepartmentSearch represents the model behind the search form about `app\modules\general\models\TblDepartment`.
 */
class TblDepartmentSearch extends TblDepartment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['department_id', 'department', 'local_name', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = TblDepartment::find();

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
        ]);

        $query->andFilterWhere(['like', 'department_id', $this->department_id])
                ->andFilterWhere(['like', 'department', $this->department])
                ->andFilterWhere(['like', 'local_name', $this->local_name]);
        $query->orderBy(['seq_no' => SORT_ASC]);


        return $dataProvider;
    }

}
