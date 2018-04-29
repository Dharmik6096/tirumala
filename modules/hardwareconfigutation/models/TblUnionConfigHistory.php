<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;

/**
 * This is the model class for table "tbl_union_config_history".
 *
 * @property integer $id
 * @property string $union_config_code
 * @property double $perc_disp_recp_milk
 * @property integer $min_member_age
 * @property integer $manual_days_collection
 * @property integer $audit_response_time
 * @property double $auto_audit_resolution
 * @property double $range_end
 * @property integer $is_delete
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblUnionConfigHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['union_config_code'], 'required'],
//            [['perc_disp_recp_milk', 'auto_audit_resolution', 'range_end'], 'number'],
//            [['min_member_age', 'manual_days_collection', 'audit_response_time'], 'integer'],
//            [['union_config_code'], 'string', 'max' => 11],
//            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 14],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['operation_type'], 'string', 'max' => 10],
            [['union_config_code', 'perc_disp_recp_milk', 'auto_audit_resolution', 'range_end', 'min_member_age', 'manual_days_collection', 'audit_response_time', 'created_by', 'updated_by', 'deleted_by', 'flg_sentbox_entry', 'operation_type'], 'safe'],
            [['created_at', 'updated_at', 'deleted_at', 'sync_timestamp', 'history_created_at', 'is_delete', 'is_active','union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_config_code' => Yii::t('app', 'Union Config Code'),
            'perc_disp_recp_milk' => Yii::t('app', 'Perc Disp Recp Milk'),
            'min_member_age' => Yii::t('app', 'Min Member Age'),
            'manual_days_collection' => Yii::t('app', 'Manual Days Collection'),
            'audit_response_time' => Yii::t('app', 'Audit Response Time'),
            'auto_audit_resolution' => Yii::t('app', 'Auto Audit Resolution'),
            'range_end' => Yii::t('app', 'Range End'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUnionConfigHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnionConfigHistoryQuery(get_called_class());
    }
}
