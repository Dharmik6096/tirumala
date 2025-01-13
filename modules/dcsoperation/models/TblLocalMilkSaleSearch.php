<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblLocalMilkSale;

/**
 * TblLocalMilkSaleSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblLocalMilkSale`.
 */
class TblLocalMilkSaleSearch extends TblLocalMilkSale {

    public $member_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['local_milk_sale_code', 'datetime_of_sale', 'milk_type_code', 'milk_class', 'shift_code', 'qty', 'qty_mode', 'converted_qty', 'converted_qty_mode', 'rate', 'discount', 'amount', 'credit', 'coupon', 'cash', 'member_code', 'payment_mode', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'consumer_code', 'consumer_type', 'federation_code', 'federation_code', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'f_route_code'], 'safe'],
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

        $query = TblLocalMilkSale::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['memberCode', 'dcsCode', 'dcsCode.unionCode']);

        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['tbl_unions.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else
            $query->andFilterWhere(['tbl_unions.union_code' => $this->union_code]);

        if (!empty($this->f_union_code)) {
            $query->andFilterWhere(['tbl_local_milk_sale.union_code' => $this->f_union_code]);
        }
        if (!empty($this->f_dcs_code)) {
            $query->andFilterWhere(['tbl_local_milk_sale.dcs_code' => $this->f_dcs_code]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_local_milk_sale.payment_mode' => $this->payment_mode,
            'rate' => $this->rate,
        ]);

        $query->andFilterWhere(['like', 'tbl_local_milk_sale.local_milk_sale_code', $this->local_milk_sale_code])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'cash', $this->cash])
                ->andFilterWhere(['like', 'coupon', $this->coupon])
                ->andFilterWhere(['like', 'credit', $this->credit])
                ->andFilterWhere(['like', 'discount', $this->discount])
                ->andFilterWhere(['like', 'tbl_local_milk_sale.member_code', $this->member_code])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_name]);

        return $dataProvider;
    }

}
