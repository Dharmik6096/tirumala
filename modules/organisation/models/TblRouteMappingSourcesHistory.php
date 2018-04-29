<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_route_mapping_sources_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property integer $route_mapping_source_code
 * @property string $route_code
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 */
class TblRouteMappingSourcesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_route_mapping_sources_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by', 'history_created_at', 'created_at', 'updated_at', 'operation_type', 'route_mapping_source_code', 'is_active'], 'safe'],
//            [['route_mapping_source_code', 'is_active'], 'integer'],
//            [['route_code', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'route_mapping_source_code' => Yii::t('app', 'Route Mapping Source Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblRouteMappingSourcesHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRouteMappingSourcesHistoryQuery(get_called_class());
    }
}
