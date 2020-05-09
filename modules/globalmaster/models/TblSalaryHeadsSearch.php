<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblSalaryHeads;

/**
 * TblSalaryHeadsSearch represents the model behind the search form about `app\models\TblSalaryHeads`.
 */
class TblSalaryHeadsSearch extends TblSalaryHeads {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['salary_head_code', 'salary_head_type', 'created_at', 'deleted_at', 'flg_sentbox_entry', 'salary_head_name', 'sync_status', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'updated_by', 'local_name'], 'safe'],
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
        $query = TblSalaryHeads::find();
        //$query->joinWith(['tblSalaryHeadsLocals']);
        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['salary_head_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['salary_head_name' => SORT_ASC]],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_salary_heads.salary_head_type' => $this->salary_head_type,
            'tbl_salary_heads.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_salary_heads.salary_head_code', $this->salary_head_code])
                ->andFilterWhere(['like', 'salary_head_name', $this->salary_head_name])
                ->andFilterWhere(['like', 'local_name', $this->local_name]);

        return $dataProvider;
    }

}
