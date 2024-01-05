<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblGrnInstallment;

/**
 * TblGrnInstallmentSearch represents the model behind the search form about `app\modules\product\models\TblGrnInstallment`.
 */
class TblGrnInstallmentSearch extends TblGrnInstallment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['grn_installment_code', 'grn_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'installment_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'main_amount', 'installment_amount', 'installment_status', 'originating_type'], 'safe'],
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
        $query = TblGrnInstallment::find()->where(['grn_code' => $this->grn_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
