<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblArea;

/**
 * TblAreaSearch represents the model behind the search form about `app\modules\geo\models\TblArea`.
 */
class TblAreaSearch extends TblArea {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['area_code', 'state_code', 'area_name', 'local_name', 'address', 'local_address', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code'], 'safe'],
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
        $query = TblArea::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (Yii::$app->session->get('Unions') !== '')
            $query->andFilterWhere([ 'tbl_area.union_code' => explode(',', Yii::$app->session->get('Unions'))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_area.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'area_code', $this->area_code])
                ->andFilterWhere(['like', 'tbl_area.area_name', $this->area_name])
                ->andFilterWhere(['like', 'local_name', $this->local_name])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'local_address', $this->local_address])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);
        return $dataProvider;
    }
}
