<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblRouteMappingSources;

/**
 * TblOrganizationLatLongApplicabilitySearch represents the model behind the search form about `app\modules\organisation\models\TblRouteMappingSources`.
 */
class TblOrganizationLatLongApplicabilitySearch extends TblOrganizationLatLongApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['organization_latlong_code','user_code','applicable_for','applicable_code','union_code','originating_org_code','originating_org_type','originating_type','updated_at','updated_by','created_at','created_by'], 'safe'],
            // [['is_active'], 'integer'],
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
        $query = TblOrganizationLatLongApplicability::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'capacity' => $this->capacity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_code' => $this->user_code,
        ]);


        return $dataProvider;
    }

}
