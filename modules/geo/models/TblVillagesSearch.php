<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblVillages;

/**
 * TblVillagesSearch represents the model behind the search form about `app\models\TblVillages`.
 */
class TblVillagesSearch extends TblVillages {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['village_code', 'created_at', 'district_name', 'sub_district_name', 'updated_at', 'village_name', 'created_by', 'sub_district_code', 'updated_by', 'state', 'district'], 'safe'],
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



        $query = TblVillages::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['village_name' => SORT_ASC]],
        ]);
        $this->load($params);

        if (isset($_GET['TblVillagesSearch']) && !$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['subDistrictCode', 'subDistrictCode.districtCode', 'subDistrictCode.districtCode.stateCode']);
        if (isset($this->state)) {
            $query->andwhere(['tbl_states.state_code' => $this->state]);
        }
        if (\Yii::$app->session->get('Districts') !== '' && isset($this->district)) {
//                $query->andWhere(['tbl_districts.district_code'=> explode(',', \Yii::$app->session->get('Districts'))]);
            $query->andWhere(['tbl_districts.district_code'=> $this->district]);
        }elseif (isset($this->district)) {
                $query->andFilterWhere(['like','tbl_districts.district_code', $this->district]);
        }
        $query->andFilterWhere([
            'tbl_villages.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_villages.village_code', $this->village_code])
                ->andFilterWhere(['like', 'village_name', $this->village_name])
                ->andFilterWhere(['like', 'tbl_districts.district_name', $this->district_name])
                ->andFilterWhere(['=', 'tbl_villages.sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_sub_districts.sub_district_name', $this->sub_district_name]);

        return $dataProvider;
    }

}
