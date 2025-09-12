<?php

namespace app\modules\import\models;

use Yii;
use yii\db\ActiveQuery;
use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_import_file_log".
 *
 * @property integer $log_id
 * @property string $file_type
 * @property string $file_name
 * @property string $file_path
 * @property integer $total_count
 * @property integer $success_count
 * @property integer $error_count
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $status
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $response_msg
 * @property string $error_file_path
 */
class TblImportFileLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_import_file_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['file_type', 'file_name', 'file_path', 'union_code', 'created_by', 'updated_by', 'response_msg', 'error_file_path', 'cron_pick_datetime'], 'safe'],
                [['total_count', 'success_count', 'error_count', 'status', 'process_type', 'success_file_path'], 'safe'],
                [['created_at', 'updated_at', 'pick_datetime', 'response_datetime'], 'safe'],
                [['process_type'], 'default', 'value' => 'background', 'on' => ['member', 'rateapplicability', 'product_sale', 'product_sale_member', 'sale_rate_applicability', 'product_master', 'product_sale_rate', 'member_rateclass', 'product_sale_batch', 'product_sale_member_batch', 'product_sale_rate_gyan', 'member_share_detail_import', 'member_incentive_detail_import', 'member_update_special_code']],
                [['process_type'], 'default', 'value' => 'SP', 'on' => ['bmc_collection', 'milk_collection', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_allow', 'milk_collection_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_bmc_can', 'bmc_collection_antibiotic', 'bmc_weight_collection', 'bmc_quality_test', 'milk_collection_dpu_data', 'milk_collection_other_data', 'member_billing_import', 'vendor_billing_import', 'milk_collection_qty', 'sample_milk_collection']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'file_type' => Yii::t('app', 'File Type'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_path' => Yii::t('app', 'File Path'),
            'total_count' => Yii::t('app', 'Total Count'),
            'success_count' => Yii::t('app', 'Success Count'),
            'error_count' => Yii::t('app', 'Error Count'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Import Datetime'),
            'created_by' => Yii::t('app', 'Import By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'response_msg' => Yii::t('app', 'Response Msg'),
            'error_file_path' => Yii::t('app', 'Error Data'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPickRecords($ids = [], $limit = 100, $type = [], $considerPickData = true) {
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));

        $query = $this->find()
                ->where(['status' => $this->status]);
        if (!empty($ids)) {
            $query->andWhere(['log_id' => $ids]);
        } else {
            $query->limit($limit);
        }
        $query->orderBy(['log_id' => SORT_ASC]);

        if (!empty($type)) {
            $query->andWhere(['process_type' => $type]);
        }

        if ($considerPickData) {
            $pendingDataQuery = $this->find()
                    ->where(['status' => 1])
                    ->andWhere(['<', 'tbl_import_file_log.pick_datetime', $datetime]);

            if (!empty($type)) {
                $pendingDataQuery->andWhere(['tbl_import_file_log.process_type' => $type]);
            }

            $pendingDataQuery->orderBy(['log_id' => SORT_ASC])->limit(5);

            return $unionQuery = (new ActiveQuery(TblImportFileLog::className()))->from([
                        'pending_data' => $query->union($pendingDataQuery, TRUE)
                    ])->all();
        } else {
            return $query->all();
        }
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['log_id' => $value]);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function updateCronPickedDate($value) {
        return $this->updateAll(['cron_pick_datetime' => date('Y-m-d H:i:s')], ['log_id' => $value->log_id]);
    }

}
