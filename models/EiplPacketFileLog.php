<?php

namespace app\models;

use Yii;

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

    public $union_code;

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
            [['dcs_code', 'file_path', 'file_name', 'created_by', 'updated_by'], 'string'],
            [['file_status', 'total_record', 'processed_record'], 'integer'],
            [['created_at', 'updated_at', 'source_type','union_code'], 'safe'],
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
        ];
    }

    public function getRecords() {
        return $this->find()->where(['file_status' => 0])->all();
    }

}
