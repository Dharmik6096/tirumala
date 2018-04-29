<?php

namespace app\modules\geo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblBlocks;

/**
 * TblBlocksSearch represents the model behind the search form about `app\modules\geo\models\TblBlocks`.
 */
class TblBlocksSearch extends TblBlocks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_code', 'created_at', 'district_name','sub_district_name','updated_at', 'block_name', 'created_by', 'sub_district_code', 'updated_by', 'state', 'district'], 'safe'],
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
    
    public function search($params) {


        
        $query = TblBlocks::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['block_name' => SORT_ASC]],
        ]);
        $this->load($params);

        if (isset($_GET['TblBlocksSearch']) && !$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith([ 'subDistrictCode', 'subDistrictCode.districtCode', 'subDistrictCode.districtCode.stateCode']);
        $query->andwhere(['tbl_states.state_code' => $this->state]);

        if(\Yii::$app->session->get('Districts')!==''){
//                $query->andWhere(['tbl_districts.district_code'=> explode(',', \Yii::$app->session->get('Districts'))]);
                $query->andWhere(['tbl_districts.district_code'=> $this->district]);
        }else
                $query->andFilterWhere(['like','tbl_districts.district_code', $this->district]);

        $query->andFilterWhere([
            'tbl_blocks.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_blocks.block_code', $this->block_code])
                ->andFilterWhere(['like', 'block_name', $this->block_name])
                ->andFilterWhere(['like', 'tbl_districts.district_name', $this->district_name])
                ->andFilterWhere(['=', 'tbl_blocks.sub_district_code', $this->sub_district_code])
                ->andFilterWhere(['like', 'tbl_sub_districts.sub_district_name', $this->sub_district_name]);

        return $dataProvider;
    }
}
