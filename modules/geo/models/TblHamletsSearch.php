<?php

namespace app\modules\geo\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblHamlets;

/**
 * TblHamletsSearch represents the model behind the search form about `app\models\TblHamlets`.
 */
class TblHamletsSearch extends TblHamlets
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hamlet_code', 'created_at', 'village_name','district_name','sub_district_name', 'hamlet_name', 'updated_at', 'created_by', 'updated_by','state','district','sub_district','village_code'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblHamlets::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['hamlet_name'=>SORT_ASC]],
        ]);

        $this->load($params);
        $query->joinWith(['villageCode','villageCode.subDistrictCode.districtCode','villageCode.subDistrictCode.districtCode.stateCode','villageCode.subDistrictCode.districtCode.stateCode']);

        if (isset($_GET['TblHamletsSearch']) && ! $this->validate()) {
            return $dataProvider;
        }

       $query->andwhere(['tbl_states.state_code' => $this->state]);



        if (!$this->validate()) {
            return $dataProvider;
        }

        if(\Yii::$app->session->get('Districts')!==''){
//                $query->andWhere(['tbl_districts.district_code'=> explode(',', \Yii::$app->session->get('Districts'))]);
            $query->andWhere(['tbl_districts.district_code'=> $this->district]);
        }else
                $query->andFilterWhere(['like', 'tbl_districts.district_code', $this->district]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_hamlets.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_hamlets.hamlet_code', $this->hamlet_code])
            ->andFilterWhere(['like', 'hamlet_name', $this->hamlet_name])
            ->andFilterWhere(['like', 'tbl_districts.district_name', $this->district_name])
            ->andFilterWhere(['like', 'tbl_sub_districts.sub_district_name', $this->sub_district_name])
            ->andFilterWhere(['like', 'tbl_sub_districts.sub_district_code', $this->sub_district])
            ->andFilterWhere(['like', 'tbl_villages.village_name', $this->village_name])
            ->andFilterWhere(['like', 'tbl_villages.village_code', $this->village_code]);
          //  ->orFilterWhere(['like', 'tbl_villages.village_name', $this->village_code]);

        return $dataProvider;
    }
}
