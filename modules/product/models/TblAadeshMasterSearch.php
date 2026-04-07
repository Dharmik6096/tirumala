<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblAadeshMaster;

/**
 * TblAadeshMasterSearch represents the model behind the search form about `app\modules\product\models\TblAadeshMaster`.
 */
class TblAadeshMasterSearch extends TblAadeshMaster {

    public $aadesh_master_code_val;
    public $product_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['aadesh_master_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'product_code', 'sale_rate', 'aadesh_master_code_val', 'union_name', 'dcs_name', 'product_name', 'union_code', 'is_member_rate', 'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price'], 'safe'],
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
        if (empty($this->product_code)) {
            $subquery = TblAadeshMaster::find()->select(['max_date = max(wef_date)', 'product_code', 'is_member_rate'])->groupBy(['product_code', 'union_code', 'is_member_rate']); //->all();
            $query = TblAadeshMaster::find()->select(['tbl_aadesh_master.aadesh_master_code as aadesh_master_code', 'tbl_aadesh_master.union_code as union_code', 'tbl_aadesh_master.product_code as product_code', 'tbl_aadesh_master.sale_rate as sale_rate', 'tbl_aadesh_master.wef_date as wef_date', 'tbl_aadesh_master.is_member_rate as is_member_rate', 'tbl_aadesh_master.commission as commission', 'tbl_aadesh_master.product_mrp as product_mrp', 'tbl_aadesh_master.distributor_landing_rate as distributor_landing_rate', 'tbl_aadesh_master.sachiv_price as sachiv_price', 'tbl_aadesh_master.member_price as member_price'])->from(['u' => $subquery]);
            $query->join('inner join', 'tbl_aadesh_master', 'tbl_aadesh_master.wef_date=u.max_date and tbl_aadesh_master.product_code=u.product_code and tbl_aadesh_master.is_member_rate=u.is_member_rate');
            $query->orderBy('wef_date DESC');
        } else {
            $query = TblAadeshMaster::find();
        }

        // add conditions that should always apply here
        if (Yii::$app->request->get('id') != "") {
            $query->where(['tbl_aadesh_master.product_code' => $this->product_code]);
            $query->andwhere(['tbl_aadesh_master.union_code' => $this->union_code]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['wef_date' => SORT_DESC]],
        ]);

        $this->load($params);

        $query->joinWith(['productCode']);

        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->wef_date))
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);

        Yii::$app->general->filterByNumber($query, $this, ['sale_rate', 'product_mrp', 'distributor_landing_rate', 'sachiv_price', 'member_price']);
        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tbl_aadesh_master.is_member_rate' => $this->is_member_rate,
        ]);

        if (Yii::$app->request->get('id') == "") {
            $query->andFilterWhere(['like', 'aadesh_master_code', $this->aadesh_master_code])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);
        } else {
            $query->andFilterWhere(['like', 'aadesh_master_code', $this->aadesh_master_code_val])
                    ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name]);
        }

        $query->andFilterWhere(['like', 'created_by', $this->created_by]);
        return $dataProvider;
    }

}
