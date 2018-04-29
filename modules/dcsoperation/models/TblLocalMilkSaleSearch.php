<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblLocalMilkSale;

/**
 * TblLocalMilkSaleSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblLocalMilkSale`.
 */
class TblLocalMilkSaleSearch extends TblLocalMilkSale
{
    public $member_name;
    public $federation_code;
    public $union_code;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_milk_sale_code','member_name','cash','amount', 'payment_mode', 'milk_type', 'discount', 'quantity', 'rate','coupon','credit','milk_class','federation_code','union_code', 'account_effect', 'created_at', 'date', 'deleted_at', 'flg_sentbox_entry', 'shift_id',  'updated_at', 'collection_point_code', 'created_by', 'dcs_code', 'deleted_by', 'member_code', 'sub_center_code', 'updated_by'], 'safe'],
            [['entry_type', 'is_delete'], 'integer'],
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
        $query = TblLocalMilkSale::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['memberCode','dcsCode','dcsCode.unionCode','dcsCode.unionCode.federationCode']);
                
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_unions.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_unions.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_local_milk_sale.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_local_milk_sale.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'date' => $this->date,
            'entry_type' => $this->entry_type,
//            'tbl_local_milk_sale.is_active' => $this->is_active,
            'tbl_local_milk_sale.is_delete' => 0,
            'payment_mode' => $this->payment_mode,
            'quantity' => $this->quantity,
            'rate' => $this->rate,
            'milk_type' => $this->milk_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_local_milk_sale.local_milk_sale_code', $this->local_milk_sale_code])
            ->andFilterWhere(['like', 'account_effect', $this->account_effect])
            ->andFilterWhere(['like', 'shift_id', $this->shift_id])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'cash', $this->cash])
            ->andFilterWhere(['like', 'coupon', $this->coupon])
            ->andFilterWhere(['like', 'credit', $this->credit])
            ->andFilterWhere(['like', 'discount', $this->discount])
            ->andFilterWhere(['like', 'collection_point_code', $this->collection_point_code])
            ->andFilterWhere(['like', 'tbl_local_milk_sale.member_code', $this->member_code])
            ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_name])
            ->andFilterWhere(['like', 'tbl_local_milk_sale.sub_center_code', $this->sub_center_code]);

        return $dataProvider;
    }
}
