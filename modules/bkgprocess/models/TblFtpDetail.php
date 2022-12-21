<?php

namespace app\modules\bkgprocess\models;

use Yii;

/**
 * This is the model class for table "tbl_ftp_detail".
 *
 * @property integer $ftp_detail_code
 * @property string $ftp_connection_code
 * @property string $ftp_type
 * @property string $ftp_host
 * @property string $ftp_username
 * @property string $ftp_password
 * @property string $ftp_port
 * @property string $ftp_path
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $description
 */
class TblFtpDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ftp_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ftp_connection_code', 'ftp_type', 'ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_path', 'created_by', 'description'], 'safe'],
                [['created_at', 'ftp_mode'], 'safe'],
                [['is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ftp_detail_code' => Yii::t('app', 'Ftp Detail Code'),
            'ftp_connection_code' => Yii::t('app', 'Ftp Connection Code'),
            'ftp_type' => Yii::t('app', 'Ftp Type'),
            'ftp_host' => Yii::t('app', 'Ftp Host'),
            'ftp_username' => Yii::t('app', 'Ftp Username'),
            'ftp_password' => Yii::t('app', 'Ftp Password'),
            'ftp_port' => Yii::t('app', 'Ftp Port'),
            'ftp_path' => Yii::t('app', 'Ftp Path'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblFtpDetailQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFtpDetailQuery(get_called_class());
    }

    public function getData() {
        return $this->find()
                        ->where(['ftp_connection_code' => $this->ftp_connection_code])
                        ->one();
    }

}
