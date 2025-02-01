<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPermanentHoldAmount;

/**
 * This is the model class for table "tbl_permanent_hold_amount".
 *
 * @property integer $permanent_hold_amount_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $transaction_date
 * @property integer $payment_cycle_code
 * @property string $hold_amount
 * @property string $release_date
 * @property string $release_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblPermanentHoldAmountSearch extends TblPermanentHoldAmount
{
    public $f_union_code, $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'release_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['transaction_date', 'created_at', 'updated_at'], 'safe'],
            [['payment_cycle_code', 'originating_type'], 'safe'],
            [['hold_amount','release_date'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code','f_dcs_code'],'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'],'required','on'=>'releasepayment'],
            [['bank_code','branch_code','bank_account_no','ifsc','bank_name','branch_name','beneficiary_name','is_verified','from_date','to_date', 'release_amount'], 'safe'],
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
    public function search($params, $release = false) {
        $query = TblPermanentHoldAmount::find();
//        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_permanent_hold_amount', 'tbl_permanent_hold_amount', 'tbl_permanent_hold_amount');
        if (!$this->validate()) {
//            echo 'dd'; die;
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }
        if($release){
            $query->where(['tbl_permanent_hold_amount.release_date' => NULL]);
        }
        $query->andFilterWhere(['tbl_permanent_hold_amount.union_code' => $this->union_code])
                ->andFilterWhere(['tbl_permanent_hold_amount.plant_code' => $this->plant_code])
                ->andFilterWhere(['tbl_permanent_hold_amount.mcc_plant_code' => $this->mcc_plant_code])
                ->andFilterWhere(['tbl_permanent_hold_amount.bmc_code' => $this->bmc_code])
                ->andFilterWhere(['tbl_permanent_hold_amount.dcs_code' => $this->dcs_code]);
//                ->andWhere(['tbl_member_payment_summary_alias.payment_cycle_code' => $this->payment_cycle_code]);
        
        return $dataProvider;
    }
}
