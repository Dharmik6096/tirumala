<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "installation_identity".
 *
 * @property integer $id
 * @property string $originating_org_id
 * @property string $originating_org_type
 * @property string $organization_code
 * @property string $organization_type
 * @property string $installed
 * @property string $db_path
 * @property integer $is_delete
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 */
class InstallationIdentity extends \app\models\ChildModel
{
    public $identity;
    public $parent_code;
    public $dcs_code;
    public $parent_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'installation_identity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_type','organization_code','parent_code','parent_type','identity'], 'required'],
            [['installed'], 'string'],
//            ['organization_code','unique','message'=> Yii::t('app/validation', 'Identity has already been created.')],
            ['organization_code', function ($attribute, $params) {
                    if(!empty($this->organization_code) && !empty($this->identity)){
                        $ary = explode('-', $this->identity);
                        $check = InstallationIdentity::find()->where(['organization_code'=>$this->organization_code,'is_active'=>1])->count();
                        if($check!=0){
                            if(!empty($this->$attribute) && $ary[2]=='4'){
                                $this->addError('dcs_code', Yii::t('app/validation', 'Identity has already been created.'));
                                return false;
                            }else{
                                $this->addError($attribute, Yii::t('app/validation', 'Identity has already been created.'));
                                return false;
                            }
                        }
                    }
                },'skipOnEmpty'=> false],
            [['is_delete', 'is_active'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'sync_timestamp','dcs_code'], 'safe'],
            [['originating_org_id'], 'string', 'max' => 25],
            [['originating_org_type'], 'string', 'max' => 50],
            [['organization_code', 'organization_type'], 'string', 'max' => 255],
            [['db_path'], 'string', 'max' => 1000],
            [['dcs_code'], function ($attribute, $params) {
                    $this->validateChecked($attribute,$params);
                },'skipOnEmpty'=> false],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 14],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
        ];
    }
    
    public function validateChecked($attribute,$params){
        
        
        if(!empty($this->identity)){
            $ary = explode('-', $this->identity);
            if(empty($this->$attribute) && $ary[2]=='4'){
                $this->addError($attribute,Yii::t('app/validation',$this->getAttributeLabel($attribute).' cannot be blank.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'originating_org_id' => Yii::t('app', 'Originating Org ID'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'installed' => Yii::t('app', 'Installed'),
            'db_path' => Yii::t('app', 'Db Path'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

    /**
     * @inheritdoc
     * @return InstallationIdentityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstallationIdentityQuery(get_called_class());
    }
    
    public function getCode() {

        $national='00';
        $federation = '00';
        $union = '000';
        switch (Yii::$app->session->get('organizations_type')){
            case 'NATIONAL':
                $national = '91';
                $federation = '00';
                $union = '000';
                break;
            case 'FEDERATION':
                $federation = Yii::$app->session->get('organizations_code');
                 break;
            case 'UNION':
                $record = TblUnions::find()->select('federation_code')->where(['union_code'=>Yii::$app->session->get('organizations_code'),'is_active'=>1,'is_delete'=>0])->one();
                $federation = $record->federation_code;
                $union = Yii::$app->session->get('organizations_code');
                break;
        }

        $new_code = $national.$federation.$union;
        $len = strlen($new_code);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`id` FROM ".$len." +1)) AS UNSIGNED)) as id")
                ->from('installation_identity')
                ->where('(CAST(trim(SUBSTRING(id, 1,'.$len.')) AS UNSIGNED))="'.trim($new_code).'"')
                ->one();
        $code = (int)$val['id'] + 1 ;
        $value = $new_code . $code;
        return $value;
    }
    
    public function getIdentityOrg(){
        
        $arry=[];
        switch (Yii::$app->session->get('organizations_type')){
            case 'NATIONAL':
                $arry = ['NATIONAL-FEDERATION-2' => 'Federation', 'FEDERATION-UNION-3' => 'Union', 'UNION-DCS-4' => 'Dcs'];
                break;
            case 'FEDERATION':
                $arry = ['FEDERATION-UNION-3' => 'Union', 'UNION-DCS-4' => 'Dcs'];
                break;
            case 'UNION':
                $arry = ['UNION-DCS-4' => 'Dcs'];
                break;
        }
        
        return $arry;
    }
}
