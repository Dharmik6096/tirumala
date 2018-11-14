<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_db_config".
 *
 * @property integer $db_config_id
 * @property string $union_code
 * @property string $union_name
 * @property string $db_name
 * @property string $db_username
 * @property string $db_password
 * @property string $db_host
 * @property string $db_port
 * @property string $db_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $site_host
 */
class TblDbConfig extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_db_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'union_name', 'db_name', 'db_username', 'db_password', 'db_host', 'db_port', 'db_type', 'created_by', 'updated_by', 'site_host'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'db_config_id' => Yii::t('app', 'Db Config ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'union_name' => Yii::t('app', 'Union Name'),
            'db_name' => Yii::t('app', 'Db Name'),
            'db_username' => Yii::t('app', 'Db Username'),
            'db_password' => Yii::t('app', 'Db Password'),
            'db_host' => Yii::t('app', 'Db Host'),
            'db_port' => Yii::t('app', 'Db Port'),
            'db_type' => Yii::t('app', 'Db Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'site_host' => Yii::t('app', 'Site Host'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDbConfigQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDbConfigQuery(get_called_class());
    }

    public function activeConnection() {
        return $this->find()->where(['is_active' => 1, 'is_delete' => 0])->all();
    }

}
