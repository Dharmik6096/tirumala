<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_member_download".
 *
 * @property integer $download_id
 * @property string $dcs_code
 * @property integer $is_download
 * @property string $download_datetime
 * @property string $upload_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberDownload extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_download';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['is_download'], 'integer'],
            [['download_datetime', 'upload_datetime', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'download_id' => Yii::t('app', 'Download ID'),
            'dcs_code' => Yii::t('app', 'Society'),
            'is_download' => Yii::t('app', 'Is Download'),
            'download_datetime' => Yii::t('app', 'Download Datetime'),
            'upload_datetime' => Yii::t('app', 'Upload Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getRecord() {
        return $this->find()->where(['dcs_code' => $this->dcs_code])->one();
    }

}
