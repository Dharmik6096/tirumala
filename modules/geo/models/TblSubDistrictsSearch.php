<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblSubDistricts;

/**
 * TblSubDistrictsSearch represents the model behind the search form about `app\models\TblSubDistricts`.
 */
class TblSubDistrictsSearch extends TblSubDistricts
{
    public $distsrict_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sub_district_code', 'district_code','distsrict_name', 'created_at', 'sub_district_name', 'updated_at', 'created_by', 'district_code', 'updated_by','state','district'], 'safe'],
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
        $query = TblSubDistricts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['sub_district_name'=>SORT_ASC]],
        ]);
        $this->load($params);
        if (isset($_GET['TblSubDistrictsSearch']) && !$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['districtCode','districtCode.stateCode']);
        
        if(isset($this->state)){
            $query->andwhere(['tbl_states.state_code' => $this->state]);
        }
        
        $query->andFilterWhere([
            'tbl_sub_districts.is_active' => $this->is_active,

        ]);

        $query->andFilterWhere(['like', 'tbl_sub_districts.sub_district_code', $this->sub_district_code])
            ->andFilterWhere(['like', 'sub_district_name', $this->sub_district_name])
            ->andFilterWhere(['like', 'tbl_states.state_code', $this->state])
            ->andFilterWhere(['like', 'tbl_districts.district_code', $this->district_code])
            ->andFilterWhere(['like', 'tbl_districts.district_name', $this->distsrict_name]);

        return $dataProvider;
    }
}
