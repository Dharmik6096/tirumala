<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblRoutes;

/**
 * TblRoutesSearch represents the model behind the search form about `app\modules\organisation\models\TblRoutes`.
 */
class TblRoutesSearch extends TblRoutes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_code','bmc_code','federation_code', 'created_at','capacity','vehicle_type_code', 'return_time', 'route_length_kms', 'route_name', 'start_time',  'updated_at', 'created_by', 'union_code', 'updated_by'], 'safe'],
            [[ 'is_active'], 'integer'],
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
        $query = TblRoutes::find();



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['route_name'=>SORT_ASC]],
        ]);

        $this->load($params);

        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_routes.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }
        else{
            $query->andwhere(['tbl_routes.union_code'=> $this->union_code]);
        }
            
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->bmc_code)){
            $query->joinWith(['tblDcsBmc']);
            $query->andFilterWhere(['like', 'tbl_dcs_subcenter_bmc_info.bmc_name', $this->bmc_code]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'capacity' => $this->capacity,
            'tbl_routes.is_active' => $this->is_active,
            'vehicle_type_code' => $this->vehicle_type_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_routes.route_code', $this->route_code])
            ->andFilterWhere(['like', 'return_time', $this->return_time])
            ->andFilterWhere(['like', 'route_length_kms', $this->route_length_kms])
            ->andFilterWhere(['like', 'route_name', $this->route_name])
            ->andFilterWhere(['like', 'start_time', $this->start_time]);

        return $dataProvider;
    }
}
