<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblHeadLoad;

/**
 * TblHeadLoadSearch represents the model behind the search form about `app\modules\vsp\models\TblHeadLoad`.
 */
class TblHeadLoadSearch extends TblHeadLoad {

    public $federation_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['entry_type', 'head_load_code', 'created_at', 'criteria_description', 'federation_code', 'criteria_type_code', 'updated_at', 'created_by', 'dcs_code', 'union_code', 'updated_by', 'fix_value', 'min_km', 'min_qty', 'max_qty'], 'safe'],
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
        $query = TblHeadLoad::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);


        //  $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['tbl_head_load.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else
            $query->andFilterWhere(['tbl_head_load.union_code' => $this->union_code]);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_head_load.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_head_load.head_load_code', $this->head_load_code])
                ->andFilterWhere(['like', 'criteria_description', $this->criteria_description])
                ->andFilterWhere(['like', 'fix_value', $this->fix_value])
                ->andFilterWhere(['like', 'min_km', $this->min_km])
                ->andFilterWhere(['like', 'tbl_head_load.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_head_load_criteria.criteria_name', $this->criteria_type_code])
                ->andFilterWhere(['like', 'min_qty', $this->min_qty])
                ->andFilterWhere(['like', 'max_qty', $this->max_qty]);

        return $dataProvider;
    }

}
