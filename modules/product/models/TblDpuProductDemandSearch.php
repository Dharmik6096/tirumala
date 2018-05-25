<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblDpuProductDemand;

/**
 * TblDpuProductDemandSearch represents the model behind the search form about `app\modules\product\models\TblDpuProductDemand`.
 */
class TblDpuProductDemandSearch extends TblDpuProductDemand {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['Id', 'Status'], 'integer'],
            [['trDate', 'shift', 'bmc_code', 'dcs_code', 'member_code', 'product_code', 'ApprovedDate', 'CreateOnUtc', 'CreatedBy', 'UpdateOnUtc', 'UpdatedBy', 'ProductStatus'], 'safe'],
            [['PPrice', 'PQty', 'PAmount'], 'number'],
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
        $query = TblDpuProductDemand::find();
//        $query->select(['tbl_DPU_ProductDemand.*','tbl_member.member_code', 'tbl_member.membername']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['dcsCode', 'memberCode', 'productCode', 'productCode.productGroupCode']);
//        $query->join('left join', 'tbl_member', 'tbl_member.dcs_code = tbl_dcs.dcs_code');
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
