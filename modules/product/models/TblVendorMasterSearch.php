<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblVendorMaster;

/**
 * TblVendorMasterSearch represents the model behind the search form about `app\modules\product\models\TblVendorMaster`.
 */
class TblVendorMasterSearch extends TblVendorMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vendor_master_code', 'vendor_code', 'vendor_name', 'pan_no', 'aadhaar_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'local_name'], 'safe'],
            [['originating_type', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'beneficiary_name', 'vendor_type', 'is_active'], 'integer'],
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
        $query = TblVendorMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vendor_master', 'tbl_vendor_master', 'tbl_vendor_master');
        $query->andFilterWhere(['like', 'vendor_master_code', $this->vendor_master_code])
                ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
                ->andFilterWhere(['like', 'pan_no', $this->pan_no])
                ->andFilterWhere(['like', 'adhar_no', $this->adhar_no])
                ->andFilterWhere(['like', 'vendor_type', $this->vendor_type])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
