<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblMccBillHeadCriteriaApplicability;

/**
 * TblMccBillHeadCriteriaApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadApplicability`.
 */
class TblMccBillHeadCriteriaApplicabilitySearch extends TblMccBillHeadCriteriaApplicability {

    public $mcc_name, $code_ex,$criteria_applicabilty_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['criteria_applicabilty_code'], 'integer'],
                [['created_at', 'created_by', 'updated_at', 'updated_by', 'bill_head_code', 'union_code', 'applicable_for', 'applicable_code', 'mcc_name', 'code_ex'], 'safe'],
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

        
        $query = TblMccBillHeadCriteriaApplicability::find();
        $query->where(['tbl_mcc_bill_head_criteria_applicability.criteria_code' => $this->criteria_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
       $query->joinWith(['mainCustomerCode', 'bmcCode', 'mccPlantCode', 'plantCode', 'customerType']);

       $query->andFilterWhere(['like', 'tbl_mcc_bill_head_criteria_applicability.applicable_code', $this->applicable_code])
               ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->mcc_name])
               ->andFilterWhere(['like', 'tbl_mcc_bill_head_criteria_applicability.union_code', $this->union_code]);

        return $dataProvider;
    }

}
