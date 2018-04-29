<?php

namespace app\modules\globalmaster\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblCasteCategory;

/**
 * TblCasteCategorySearch represents the model behind the search form about `app\models\TblCasteCategory`.
 */
class TblCasteCategorySearch extends TblCasteCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['caste_category_code','caste_category_name', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
        $query = TblCasteCategory::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['caste_category_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

         $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['caste_category_name'=>SORT_ASC]],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_caste_category.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_caste_category.caste_category_code', $this->caste_category_code])
                ->andFilterWhere(['like', 'tbl_caste_category.caste_category_name', $this->caste_category_name]);
        return $dataProvider;
    }
}
