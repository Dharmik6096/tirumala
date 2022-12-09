<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeMaster;

/**
 * TblSchemeMasterSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeMaster`.
 */
class TblSchemeMasterSearch extends TblSchemeMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_id', 'is_active', 'originating_type'], 'integer'],
                [['scheme_id', 'is_active', 'originating_type', 'scheme_name', 'start_date', 'end_date', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblSchemeMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'scheme_id' => $this->scheme_id,
            'tbl_scheme_master.is_active' => $this->is_active,
        ]);

        if (!empty($this->start_date))
            $query->andFilterWhere(['like', 'cast(tbl_scheme_master.start_date as DATE)', date('Y-m-d', strtotime($this->start_date))]);
        if (!empty($this->end_date))
            $query->andFilterWhere(['like', 'cast(tbl_scheme_master.end_date as DATE)', date('Y-m-d', strtotime($this->end_date))]);

        $query->andFilterWhere(['like', 'scheme_name', $this->scheme_name])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
