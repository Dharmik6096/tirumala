<?php

namespace app\modules\setting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\setting\models\TblGeneralConfig;

/**
 * TblGeneralConfigSearch represents the model behind the search form about `app\modules\setting\models\TblGeneralConfig`.
 */
class TblGeneralConfigSearch extends TblGeneralConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_code', 'is_active'], 'safe'],
            [['module_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'safe'],
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
        $query = TblGeneralConfig::find();

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
        Yii::$app->general->filterByNumber($query, $this, ['config_code']);

        // grid filtering conditions
        $query->andFilterWhere([
//            'config_code' => $this->config_code,
            'is_active' => $this->is_active,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'module_name', $this->module_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
