<?php

namespace app\modules\syncutility\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_security".
 *
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
 */
class TblSecurity extends \app\models\ChildModel {

    public $parent_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_sectie';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['col_e', 'unique'],
            [['col_b', 'col_c', 'col_d', 'col_e'], 'required'],
            [['col_c', 'col_f'], 'string', 'min' => 6, 'max' => 16],
            [['col_b'], 'string', 'min' => 6, 'max' => 6],
            [['col_b'], 'number'],
            [['col_f'], function ($attribute, $params) {
            $this->validateZipKey($attribute, $params);
        }, 'skipOnEmpty' => false],
            [['created_at', 'updated_at', 'sync_timestamp'], 'safe'],
            [['col_c', 'col_d', 'col_e', 'col_g'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [

            'col_a' => Yii::t('app', 'ID'), // AUTO_INC : Primary Key 
            'col_b' => Yii::t('app', 'App Activation Key'), // User Define app_activation key
            'col_c' => Yii::t('app', 'Data Encryption Key'), // User Define data encryption key
            'col_d' => Yii::t('app', 'Organisation Type'), // organisation type
            'col_e' => Yii::t('app', 'Organisation code'), // organisation code
            'col_f' => Yii::t('app', 'File Protection Key'), // Zip Protection key
            'col_g' => Yii::t('app', 'Col G'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblSecurityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSecurityQuery(get_called_class());
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'col_e']);
    }

    public function getRecord($org_code = '') {
        $org_code = empty($org_code) ? Yii::$app->session->get('organizations_code') : $org_code;
        return $this->find()->where(['col_e' => $org_code])->one();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            if ($this->hasAttribute('flg_sentbox_entry')) {
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    $this->flg_sentbox_entry = 'Y';
                }
            }

            if ($this->hasAttribute('sync_status')) {
                $this->sync_status = 'U';
            }
            if ($insert) {
                if ($this->hasAttribute('created_by')) {
                    $this->created_by = $user;
                }
                if ($this->hasAttribute('created_at')) {
                    $this->created_at = date('Y-m-d H:i:s');
                }
            } else {
                if ($this->hasAttribute('updated_by')) {
                    $this->updated_by = $user;
                }
                if ($this->hasAttribute('updated_at')) {
                    $this->updated_at = date('Y-m-d H:i:s');
                }
            }
            $key = Yii::$app->general->SetSecurityEncryptionKey($this->col_d, $this->col_e);
            Yii::$app->encrypter->setGlobalPassword($key);
            $this->col_b = Yii::$app->general->encryptData($this->col_b);
            $this->col_c = Yii::$app->general->encryptData($this->col_c);
            if ($this->col_f != '') {
                $this->col_f = Yii::$app->general->encryptData($this->col_f);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterFind() {
        $key = Yii::$app->general->SetSecurityEncryptionKey($this->col_d, $this->col_e);
        Yii::$app->encrypter->setGlobalPassword($key);
        $this->col_b = Yii::$app->general->decryptData($this->col_b);
        $this->col_c = Yii::$app->general->decryptData($this->col_c);
        $this->col_f = Yii::$app->general->decryptData($this->col_f);
        parent::afterFind();
    }

    public function validateZipKey($attribute, $params) {
        if ($this->col_d == 'UNION') {
            if (empty($this->$attribute)) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' cannot be blank.'));
                return false;
            }
        }
    }

}
