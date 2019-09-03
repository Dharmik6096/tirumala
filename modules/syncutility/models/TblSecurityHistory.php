<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_security_history".
 *
 * @property integer $id
 * @property integer $col_a
 * @property string $col_b
 * @property string $col_c
 * @property string $col_d
 * @property string $col_e
 * @property string $col_f
 * @property string $col_g
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblSecurityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_sectie_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['col_a'], 'safe'],
            [['col_a'], 'safe'],
            [['created_at', 'updated_at', 'sync_timestamp', 'history_created_at'], 'safe'],
            [['col_b', 'col_c', 'col_d', 'col_e', 'col_f', 'col_g'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['flg_sentbox_entry', 'sync_status'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'col_a' => Yii::t('app', 'Col A'),
            'col_b' => Yii::t('app', 'Col B'),
            'col_c' => Yii::t('app', 'Col C'),
            'col_d' => Yii::t('app', 'Col D'),
            'col_e' => Yii::t('app', 'Col E'),
            'col_f' => Yii::t('app', 'Col F'),
            'col_g' => Yii::t('app', 'Col G'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
