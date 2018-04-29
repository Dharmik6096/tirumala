<?php

namespace app\modules\geo\models;

use Yii;

/**
 * This is the model class for table "tbl_blocks_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $block_code
 * @property string $block_name
 * @property string $sub_district_code
 * @property string $local_name
 */
class TblBlocksHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_blocks_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'history_created_at', 'updated_at'], 'safe'],
            [['created_by', 'operation_type', 'updated_by', 'block_code', 'block_name', 'sub_district_code', 'local_name'], 'safe'],
            [['is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'block_code' => Yii::t('app', 'Block Code'),
            'block_name' => Yii::t('app', 'Block Name'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'local_name' => Yii::t('app', 'Local Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblBlocksHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBlocksHistoryQuery(get_called_class());
    }
}
