<?php

namespace app\modules\veterinary\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\veterinary\models\TblMedicineStockTransaction;

/**
 * TblMedicineStockTransactionSearch represents the model behind the search form about `app\modules\veterinary\models\TblMedicineStockTransaction`.
 */
class TblMedicineStockTransactionSearch extends TblMedicineStockTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['medicine_id', 'union_code', 'module_name', 'module_code', 'batch_no', 'tran_datetime', 'old_value', 'new_value', 'final_value', 'expire_date', 'entry_type', 'transfer_ref_code', 'transfer_ref_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblMedicineStockTransaction::find()->where(['medicine_id' => $this->medicine_id, 'union_code' => $this->union_code, 'module_name' => $this->module_name, 'module_code' => $this->module_code,]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
