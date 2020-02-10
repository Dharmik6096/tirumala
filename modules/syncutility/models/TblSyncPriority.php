<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_sync_priority".
 *
 * @property string $table_name
 * @property string $sync_type
 * @property integer $sequence_no
 */
class TblSyncPriority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sync_priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_name', 'sync_type'], 'required'],
            [['table_name', 'sync_type'], 'string'],
            [['sequence_no'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'table_name' => Yii::t('app', 'Table Name'),
            'sync_type' => Yii::t('app', 'Sync Type'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
        ];
    }
}
