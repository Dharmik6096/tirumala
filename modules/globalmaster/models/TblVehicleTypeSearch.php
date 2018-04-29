<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblVehicleType;

/**
 * TblVehicleTypeSearch represents the model behind the search form about `app\modules\globalmaster\models\TblVehicleType`.
 */
class TblVehicleTypeSearch extends TblVehicleType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_type_code', 'is_active'], 'integer'],
            [['vehicle_type_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name'], 'safe'],
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
        $query = TblVehicleType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['vehicle_type_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vehicle_type_code' => $this->vehicle_type_code,
            'is_active' => $this->is_active,
           
        ]);

        $query->andFilterWhere(['like', 'vehicle_type_name', $this->vehicle_type_name]);
         

        return $dataProvider;
    }
}
