<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblMiscellaneous;

/**
 * TblMiscellaneousSearch represents the model behind the search form about `app\models\TblMiscellaneous`.
 */
class TblMiscellaneousSearch extends TblMiscellaneous
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['miscellaneous_code', 'created_at', 'miscellaneous_name', 'updated_at', 'created_by','updated_by'], 'safe'],
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
     * Creates data provmiscellaneous_codeer instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvmiscellaneous_codeer
     */
    public function search($params)
    {
        $query = TblMiscellaneous::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['miscellaneous_name'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when valmiscellaneous_codeation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grmiscellaneous_code filtering conditions
        $query->andFilterWhere([
            'tbl_miscellaneous.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_miscellaneous.miscellaneous_code', $this->miscellaneous_code])
            ->andFilterWhere(['like', 'tbl_miscellaneous.miscellaneous_name', $this->miscellaneous_name]);

        return $dataProvider;
    }
}
