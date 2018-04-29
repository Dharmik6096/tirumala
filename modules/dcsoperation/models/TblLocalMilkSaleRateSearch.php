<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblLocalMilkSaleRate;

/**
 * TblLocalMilkSaleRateSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblLocalMilkSaleRate`.
 */
class TblLocalMilkSaleRateSearch extends TblLocalMilkSaleRate
{
    
    public $federation_code;
    public $union_code;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_sale_rate_code','milk_type','milk_class','rate', 'created_at', 'deleted_at', 'federation_code', 'union_code','updated_at', 'wef_date', 'created_by', 'dcs_code', 'deleted_by', 'sub_center_code', 'updated_by'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
            
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
        $query = TblLocalMilkSaleRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['dcsCode','dcsCode.unionCode','dcsCode.unionCode.federationCode','milkClass','milkType']);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_unions.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_unions.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_local_milk_sale_rate.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_local_milk_sale_rate.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_local_milk_sale_rate.is_active' => $this->is_active,
            'tbl_local_milk_sale_rate.is_delete' => 0,
            'rate' => $this->rate,
        ]);

        $query->andFilterWhere(['like', 'tbl_local_milk_sale_rate.local_sale_rate_code', $this->local_sale_rate_code])
            ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type])
            ->andFilterWhere(['like', 'tbl_milk_class.class_name', $this->milk_class])
            ->andFilterWhere(['like', 'wef_date',(!empty($this->wef_date))?date('Y-m-d', strtotime ($this->wef_date)):''])
           ->andFilterWhere(['like', 'sub_center_code', $this->sub_center_code]);

        return $dataProvider;
    }
}
