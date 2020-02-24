<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblSaleInstallments;

/**
 * TblProductSaleSearch represents the model behind the search form about `app\modules\payment\models\TblProductSale`.
 */
class TblSaleInstallmentsSearch extends TblSaleInstallments {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['sale_code', 'dcs_code', 'union_code', 'member_code',], 'safe'],
                [['main_amount', 'installment_amount'], 'number'],
                [['is_active', 'installment_status'], 'integer'],
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
        $query = TblSaleInstallments::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (Yii::$app->session->get('Unions') !== '') {
            // $query->andFilterWhere([ 'tbl_sale_installments.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
            //$query->andwhere(['tbl_sale_installments.dcs_code'=> $this->dcs_code]);
        } else {
            //$query->andwhere(['tbl_sale_installments.union_code'=> $this->union_code]);
            // $query->andFilterWhere(['tbl_sale_installments.dcs_code'=> $this->dcs_code]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->member_code) {
            $query->joinWith(['memberCode']);
            $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            //'sale_date_time' => $this->sale_date_time,
            'main_amount' => $this->main_amount,
            'installment_amount' => $this->installment_amount,
                //'tbl_sale_installments.is_active' => $this->is_active,            
                //'installment_status' => $this->installment_status,
        ]);

        $query->andFilterWhere(['like', 'sale_code', $this->sale_code]);

        return $dataProvider;
    }

}
