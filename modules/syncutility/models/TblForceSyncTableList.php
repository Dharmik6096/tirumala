<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_force_sync_table_list".
 *
 * @property integer $id
 * @property string $table_name
 * @property string $table_desc
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblForceSyncTableList extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_force_sync_table_list';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'originating_type'], 'safe'],
                [['created_at', 'updated_at'], 'safe'],
                [['table_name', 'table_desc'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'table_name' => Yii::t('app', 'Table Name'),
            'table_desc' => Yii::t('app', 'Table Desc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
