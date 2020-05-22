<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblConfigTxnResult;

/**
 * TblConfigTxnResultSearch represents the model behind the search form about `app\modules\tankermovement\models\TblConfigTxnResult`.
 */
class TblConfigTxnResultSearch extends TblConfigTxnResult {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_txn_result_code', 'config_result', 'config_for', 'ref_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['config_code', 'originating_type'], 'integer'],
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
        $query = TblConfigTxnResult::find()->where(['config_for' => $this->config_for,'ref_code'=>  $this->ref_code]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
