<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_packet_process".
 *
 * @property integer $packet_id
 * @property string $dcs_code
 * @property string $file_name
 * @property string $line_text
 * @property string $line_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_decrypted
 * @property integer $main_table
 * @property string $source_type
 */
class EiplPacketProcess extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_packet_process';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'line_text', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'line_no', 'file_name', 'source_type'], 'safe'],
            [['is_decrypted', 'main_table'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'packet_id' => Yii::t('app', 'Packet ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'file_name' => Yii::t('app', 'File Name'),
            'line_text' => Yii::t('app', 'Line Text'),
            'line_no' => Yii::t('app', 'Line No'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_decrypted' => Yii::t('app', 'Is Decrypted'),
            'main_table' => Yii::t('app', 'Main Table'),
        ];
    }

}
