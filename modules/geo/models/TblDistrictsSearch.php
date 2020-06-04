<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblDistricts;

/**
 * TblDistrictsSearch represents the model behind the search form about `app\models\TblDistricts`.
 */
class TblDistrictsSearch extends TblDistricts {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['district_code', 'created_at', 'district_name', 'updated_at', 'created_by', 'state_code', 'updated_by'], 'safe'],
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
        $query = TblDistricts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['district_name' => SORT_ASC]],
        ]);
        $this->load($params);
        //$query->andwhere(['state_code' => explode(',', Yii::$app->session->get('States'))]);
        $query->andwhere(['tbl_districts.state_code' => $this->state_code]);
        if (Yii::$app->session->get('Districts') != '') {
            $array = explode(',', Yii::$app->session->get('Districts'));
            $query->andwhere(['tbl_districts.district_code' => $array]);
        }

        if (isset($_GET['TblDistrictsSearch']) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_districts.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_districts.district_code', $this->district_code])
                ->andFilterWhere(['like', 'district_name', $this->district_name]);

        return $dataProvider;
    }

}
