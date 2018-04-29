<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblVillageMiscellaneous;

/**
 * TblVillageMiscellaneousSearch represents the model behind the search form about `app\models\TblVillageMiscellaneous`.
 */
class TblVillageMiscellaneousSearch extends TblVillageMiscellaneous
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['village_miscellaneous_code', 'created_at','description', 'updated_at', 'created_by', 'updated_by', 'village_code'], 'safe'],
            [['is_active', 'miscellaneous_code'], 'integer'],
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
        $query = TblVillageMiscellaneous::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $query->joinWith(['mescellaneousCode']);

        $query->andwhere(['village_code' => Yii::$app->getRequest()->getQueryParam('id')]);
        
        $this->load($params);
        
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_village_miscellaneous.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'village_miscellaneous_code', $this->village_miscellaneous_code])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tbl_miscellaneous.miscellaneous_name', $this->miscellaneous_code]);

       
        return $dataProvider;
    }
}
