<?php

namespace app\modules\payment\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\transporter\models\TblTransporter;
use app\modules\organisation\models\TblDcsBmc;
/**
 * This is the model class for table "tbl_vehicle_payment".
 *
 * @property integer $vehicle_payment_code
 * @property integer $transporter_payment_code
 * @property string $bmc_code
 * @property string $vehicle_code
 * @property string $route_code
 * @property string $coll_qty
 * @property string $coll_kg_fat
 * @property string $coll_kg_snf
 * @property string $disp_qty
 * @property string $disp_kg_fat
 * @property string $disp_kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $cd_qty_diff
 * @property string $cd_kg_fat_diff
 * @property string $cd_kg_snf_diff
 * @property string $rd_qty_diff
 * @property string $rd_kg_fat_diff
 * @property string $rd_kg_snf_diff
 * @property integer $no_of_days
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblVehiclePayment extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transporter_payment_code', 'no_of_days'], 'integer'],
            [['bmc_code', 'vehicle_code', 'route_code', 'created_by', 'updated_by'], 'string'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'total_amount', 'total_deduction', 'final_amount'], 'number'],
            [['created_at', 'updated_at','from_date','to_date','transporter_code','union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_payment_code' => Yii::t('app', 'Vehicle Payment'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'route_code' => Yii::t('app', 'Route'),
            'coll_qty' => Yii::t('app', 'Coll Qty'),
            'coll_kg_fat' => Yii::t('app', 'Coll Kg Fat'),
            'coll_kg_snf' => Yii::t('app', 'Coll Kg Snf'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg Fat'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg Fat'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg Snf'),
            'cd_qty_diff' => Yii::t('app', 'Cd Qty Diff'),
            'cd_kg_fat_diff' => Yii::t('app', 'Cd Kg Fat Diff'),
            'cd_kg_snf_diff' => Yii::t('app', 'Cd Kg Snf Diff'),
            'rd_qty_diff' => Yii::t('app', 'Rd Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'Rd Kg Fat Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'Rd Kg Snf Diff'),
            'no_of_days' => Yii::t('app', 'No Of Days'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }
    
    
    public function vehicleDetail($model){
        $bmc_code = $model->bmc_code;
        $transporter_code = $model->transporter_code;
        $from_date = date('Y-m-d', strtotime($model->from_date));
        $to_date = date('Y-m-d', strtotime($model->to_date));
        $result = \Yii::$app->db->createCommand("{CALL [sp_vehicle_summary_temp](:bmc_code,:transporter_code,:from_date,:to_date)}")
                ->bindValue(':bmc_code', $bmc_code)
                ->bindValue(':transporter_code', $transporter_code)
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date);
        $query = $result->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'defaultOrder' => ['vehicle_code' => SORT_ASC],
                'attributes' => [
                    'vehicle_code',
                    'transporter_code',
                    'transporter_code',
                    'bmc_code',
                    'bmc_name',
                    'coll_qty',
                    'coll_kg_fat',
                    'coll_kg_snf',
                    'disp_qty',
                    'disp_kg_fat',
                    'disp_kg_snf',
                    'rec_qty',
                    'rec_kg_fat',
                    'rec_kg_snf',
                    'cd_qty_diff',
                    'cd_kg_fat_diff',
                    'cd_kg_snf_diff',
                    'rd_qty_diff',
                    'rd_kg_fat_diff',
                    'rd_kg_snf_diff',
                    'no_of_days',
                    'total_amount',
                    'total_deduction',
                    'final_amount',
                ],
            ],
        ]);
        return $dataProvider;
    }
    
    
    
    public function existData($model){
        $data = $this->find()
                ->andWhere(['transporter_payment_code' => $model->transporter_payment_code])
                ->all();
        return $data;
    }
    
    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }
    
    public function getBmcCode(){
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }
    
}
