<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\TblEiplAppWidget;

/**
 * TblEiplAppWidgetSearch represents the model behind the search form about `app\modules\usermanagement\models\TblEiplAppWidget`.
 */
class TblEiplAppWidgetSearch extends TblEiplAppWidget {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['widget_id', 'is_active', 'widget_for'], 'integer'],
            [['widget_name', 'widget_icon', 'widget_api', 'redirect_path', 'widget_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
    public function search($params, $searchFor = 1) {
        $query = TblEiplAppWidget::find()->where(['is_active' => 1, 'widget_for' => $searchFor]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['app_type' => $this->app_type]);

        return $dataProvider;
    }

}
