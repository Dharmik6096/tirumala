<?php

namespace app\models;

use Yii;
use app\modules\organisation\models\TblUnions;
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
 * @property string $source_type
 */
class EiplPacketFileLog extends ChildModel {

    public $dpu_type;

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
            [['union_code'], 'default', 'value' => Yii::$app->session->get('organizations_code')],
            [['dcs_code', 'file_path', 'file_name', 'created_by', 'updated_by'], 'string'],
            [['file_status', 'total_record', 'processed_record'], 'integer'],
            [['created_at', 'updated_at', 'source_type', 'union_code'], 'safe'],
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
            'created_at' => Yii::t('app', 'Uploaded Datetime'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Processed Datetime'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getRecords() {
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));
        return $this->find()
                        ->Where(['or', ['status' => 0], ['status' => NULL]])
                        ->orWhere(['and', ['status' => 1], ['<', 'pick_datetime', $datetime]])
                        ->limit(50)
                        ->orderby('created_at ASC')
                        ->all();
    }

    public function updateStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['file_id' => $value]);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getUnionDpuConfig() {
        return $this->hasOne(TblUnionDpuConfig::className(), ['union_code' => 'union_code'])->where(['dpu_type' => $this->dpu_type]);
    }

}
