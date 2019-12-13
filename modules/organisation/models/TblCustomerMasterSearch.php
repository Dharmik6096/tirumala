<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * TblCustomerMasterSearch represents the model behind the search form about `app\modules\organisation\models\TblCustomerMaster`.
 */
class TblCustomerMasterSearch extends TblCustomerMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_code', 'customer_name', 'address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'local_name', 'local_address', 'gst_no', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['is_active', 'originating_type'], 'integer'],
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
        $query = TblCustomerMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_customer_master.customer_type' => $this->customer_type,
            'tbl_customer_master.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_customer_master.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_master.customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'tbl_customer_master.gst_no', $this->gst_no])
                ->andFilterWhere(['like', 'tbl_customer_master.sap_code', $this->sap_code])
                ->andFilterWhere(['like', 'tbl_customer_master.refference_code', $this->refference_code]);

        return $dataProvider;
    }

}
