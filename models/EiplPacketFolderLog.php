<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_packet_folder_log".
 *
 * @property integer $folder_id
 * @property string $dcs_code
 * @property string $datetime
 * @property integer $no_of_files
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class EiplPacketFolderLog extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_eipl_packet_folder_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['datetime', 'created_at', 'updated_at'], 'safe'],
            [['no_of_files'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'folder_id' => Yii::t('app', 'Folder ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'datetime' => Yii::t('app', 'Datetime'),
            'no_of_files' => Yii::t('app', 'No Of Files'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
