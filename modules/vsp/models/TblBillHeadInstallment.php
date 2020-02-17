<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_installment".
 *
 * @property integer $bill_head_installment_code
 * @property integer $bill_head_detail_code
 * @property string $bill_head_code
 * @property string $dcs_code
 * @property string $installement_cycle
 * @property string $installment_amount
 * @property string $installment_date
 * @property string dcs_payment_cycle_code
 */
class TblBillHeadInstallment extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bill_head_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bill_head_detail_code'], 'integer'],
            [['bill_head_code', 'dcs_code', 'installement_cycle', 'installment_amount'], 'safe'],
            [['installment_date','dcs_payment_cycle_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bill_head_installment_code' => 'Bill Head Installment Code',
            'bill_head_detail_code' => 'Bill Head Detail Code',
            'bill_head_code' => 'Bill Head Code',
            'dcs_code' => 'Dcs Code',
            'installement_cycle' => 'Installement Cycle',
            'installment_amount' => 'Installment Amount',
            'installment_date' => 'Installment Date',
        ];
    }

    public function getData($detail_id){
        return $this->find()->select(['bill_head_installment_code'])->where(['bill_head_detail_code'=>$detail_id])->all();
    }
}
