<?php

namespace app\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "identity_master".
 *
 * @property integer $id
 * @property string $parent_code
 * @property string $parent_type
 * @property string $organization_code
 * @property string $organization_type
 * @property string $parent_url
 * @property string $own_url
 * @property string $own_mac_address
 * @property string $activation_key
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $is_active
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $sync_url
 */
class IdentityMaster extends ChildModel {

    public $identity;
    public $username;
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'identity_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_by', 'updated_by'], 'integer'],
                [['username'], 'validateUniqueUsername'],
                [['identity', 'username', 'password', 'sync_url', 'organization_code', 'organization_type'], 'required'],
                [['created_at', 'is_delete', 'identity', 'parent_type', 'parent_code', 'updated_at', 'username', 'password', 'parent_url', 'own_url', 'own_mac_address', 'sync_timestamp', 'is_active', 'activation_key', 'flg_sentbox_entry', 'sync_status', 'sync_url'], 'safe'],
                [['organization_code', 'organization_type'], 'string', 'max' => 255],
        ];
    }

    public function validateUniqueUsername() {

        if (!empty($this->username)) {
            $idenRecord = $this->getIdentity();

            $check = User::find()->where(['username' => $this->username])->count();
            if ($check != 0) {
                $this->addError('username', Yii::t('app', 'This Username already taken'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'identity' => Yii::t('app', 'Identity'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'parent_url' => Yii::t('app', 'Parent URL'),
            'own_url' => Yii::t('app', 'Own URL'),
            'parent_type' => Yii::t('app', 'Parent Type'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'update_by' => Yii::t('app', 'Update By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'sync_url' => Yii::t('app', 'Sync URL'),
        ];
    }

    /**
     * @inheritdoc
     * @return IdentityMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new IdentityMasterQuery(get_called_class());
    }

    public function getIdentity() {
        return self::find()->select(['organization_type', 'organization_code', 'parent_code', 'parent_type'])->where(['is_active' => 1])->one();
    }

    public function getIdentityCode() {

        $type = $this->getIdentity();
        switch ($type->organization_type) {
            case 'NATIONAL':
                return 1;
                break;
            case 'FEDERATION':
                return 2;
                break;
            case 'UNION':
                return 3;
                break;
            case 'DCS':
                return 4;
                break;
        }
    }

    /* public function getCode() {

      $national='00';
      $federation = '00';
      $union = '000';
      switch ($this->organization_type){
      case 'NATIONAL':
      $national = '91';
      $federation = '00';
      $union = '000';
      break;
      case 'FEDERATION':
      $federation = $this->organization_code;
      break;
      case 'UNION':
      $record = TblUnions::find()->select('federation_code')->where(['union_code'=>$this->organization_code,'is_active'=>1,'is_delete'=>0])->one();
      $federation = $record->federation_code;
      $union = $this->organization_code;
      break;
      }

      $new_code = $national.$federation.$union;
      $len = strlen($new_code);
      $val = (new \yii\db\Query)
      ->select("MAX(CAST(trim(SUBSTRING(`id` FROM ".$len." +1)) AS UNSIGNED)) as id")
      ->from('identity_master')
      ->where('(CAST(trim(SUBSTRING(id, 1,'.$len.')) AS UNSIGNED))="'.trim($new_code).'"')
      ->one();
      $code = (int)$val['id'] + 1 ;

      $value = $new_code . $code;
      return $value;
      } */
}
