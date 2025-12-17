<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\TblEiplAppWidgetMapping;

/**
 * TblEiplAppWidgetMappingSearch represents the model behind the search form about `app\modules\usermanagement\models\TblEiplAppWidgetMapping`.
 */
class TblEiplAppWidgetMappingSearch extends TblEiplAppWidgetMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mapping_id', 'widget_id'], 'integer'],
            [['login_type', 'department', 'created_at', 'created_by', 'updated_at', 'updated_by', 'widget_name', 'app_type'], 'safe'],
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
        $query = TblEiplAppWidgetMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>FALSE
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mapping_id' => $this->mapping_id,
            'widget_id' => $this->widget_id,
        ]);

        $query->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'department', $this->department]);
        return $dataProvider;
    }

}
