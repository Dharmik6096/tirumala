<?php

namespace app\modules\eipldpu\models;

use Yii;
use app\modules\organisation\models\TblUnionDpuConfig;

/**
 * This is the model class for table "tbl_eipl_packet_file_log".
 *
 * @property integer $file_id
 * @property string $dcs_code
 * @property string $file_path
 * @property string $file_name
 * @property integer $file_status
 * @property integer $total_record
 * @property integer $processed_record
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $source_type
 * @property string $union_code
 * @property integer $status
 * @property string $pick_datetime
 */
class TblEiplPacketFileLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_packet_file_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_code', 'file_path', 'file_name', 'created_by', 'updated_by', 'union_code'], 'safe'],
                [['file_status', 'total_record', 'processed_record', 'source_type', 'status'], 'safe'],
                [['created_at', 'updated_at', 'pick_datetime', 'zip_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'file_id' => Yii::t('app', 'File ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_status' => Yii::t('app', 'File Status'),
            'total_record' => Yii::t('app', 'Total Record'),
            'processed_record' => Yii::t('app', 'Processed Record'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'source_type' => Yii::t('app', 'Source Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
        ];
    }

    public function getUnionDpuConfig() {
        return $this->hasOne(TblUnionDpuConfig::className(), ['union_code' => 'union_code'])->where(['dpu_type' => $this->dpu_type]);
    }

    public function getPendingData($file_id = []) {
        $query = $this->find()
                ->where(['file_status' => $this->file_status, 'status' => $this->status])
                ->andWhere(['!=', 'zip_name', '']);
        if (!empty($file_id)) {
          $query->andWhere(['file_id'=>$file_id]);
        }
        return $query->limit(25)->all();
    }

    public function updateFileStatus($ids) {
        return $this->updateAll(['status' => 1, 'updated_at' => date('Y-m-d H:i:s'), 'pick_datetime' => date('Y-m-d H:i:s')], ['file_id' => $ids]);
    }

}
