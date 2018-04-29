<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_financial_year_history".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $ending_date
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property string $operation_type
 * @property string $starting_date
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $updated_by
 */
class TblFinancialYearHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_financial_year_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'ending_date', 'history_created_at', 'starting_date', 'sync_timestamp', 'updated_at'], 'safe'],
            [['is_active', 'is_delete'], 'safe'],
            [['code'], 'safe'],
            [['created_by', 'deleted_by', 'updated_by'], 'safe'],
            [['flg_sentbox_entry', 'sync_status'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'ending_date' => Yii::t('app', 'Ending Date'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'starting_date' => Yii::t('app', 'Starting Date'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblFinancialYearHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblFinancialYearHistoryQuery(get_called_class());
    }
}
