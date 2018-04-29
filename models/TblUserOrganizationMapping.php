<?php

namespace app\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblFederations;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "tbl_user_organization_mapping".
 *
 * @property string $id
 * @property string $created_at
 * @property integer $is_active
 * @property string $organization_code
 * @property string $organization_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $user_id
 *
 * @property TblUsers $user
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 */
class TblUserOrganizationMapping extends ChildModel
{
    public $organization;
    public $federation;
    public $union;
    public $dcs;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_organization_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['federation'], 'required','on'=>'organizationMapping'],
            [['federation','union','dcs','created_at', 'deleted_at', 'updated_at','organization'], 'safe'],
            [['is_active'], 'integer'],
//            [['federation'], function ($attribute, $params) {
//                    $this->validateChecked($attribute,$params);
//                },'skipOnEmpty'=> false],
            [['organization_code', 'organization_type'], 'string', 'max' => 25],
            [['created_by','updated_by', 'user_id'], 'string', 'max' => 14],           
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'organization'=>Yii::t('app', 'Organization'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            
            'is_active' => Yii::t('app', 'Is Active'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
         
            'updated_by' => Yii::t('app', 'Updated By'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

  
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TblUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return TblUserOrganizationMappingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUserOrganizationMappingQuery(get_called_class());
    }


    public function getOrganization($id,$orgType){
        $userOrg = User::getSelectedOrganization($orgType);

        foreach ($userOrg['data'] as $row){
                $data[$row->{$userOrg['field'][0]}] = $row->{$userOrg['field'][1]};
        }
        $values = $this->find()->select('organization_code,organization_type')->where(['user_id' => $id,'is_active'=>1])->asArray()->all();
        $selected = [];
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($values, 'organization_code')) !== FALSE) {
                $selected[$key] = ['selected'=>'selected'];
            }
        }
        return ['value' => $data, 'selected' => $selected];
    }

    public function getOrganizationsArray($id,$orgType){
        
        $userOrg = User::getSelectedOrganization($orgType);
        $fedModel = new TblFederations();
        $federations = $fedModel->getActiveFederation();
        $unions = ['data'=>[],'selectedArray'=>[]];
        $dcs = ['data'=>[],'selectedArray'=>[]];
        $federations = ['data'=>$federations,'selectedArray'=>[]];
        $data=[];
        foreach ($userOrg['data'] as $row){
            $data[$row[$userOrg['field'][0]]] = $row[$userOrg['field'][1]];
        }
        $values = $this->find()->select('organization_code,organization_type')->where(['user_id' => $id,'is_active'=>1])->asArray()->all();
        $selected = [];
        $selectedArray = [];
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($values, 'organization_code')) !== FALSE) {
                $selected[$key] = ['selected'=>'selected'];
                $selectedArray[$key] = $row;
            }
        }
        
        switch ($orgType) {
            case '4' :
                $union_temp1 = $this->getUnions(0,$selectedArray);
                $federations = $this->getFederations(0,$union_temp1['selectedValue']);
                $union_temp2 = $this->getUnions($federations['selectedValue'],0);
                $unions=['data'=>$union_temp2['data'],'selectedArray'=>$union_temp1['selectedArray']];
                $dcs = $this->getDcs($union_temp1['data']);
                $dcs = ['data'=>$dcs['data'],'selectedArray'=>$selected];
                break;
            case '3' :
                $federations = $this->getFederations(0,$selectedArray);
                $unions =$this->getUnions($federations['selectedValue'],0);
                $unions['selectedArray']=$selected;
                $dcs = $this->getDcs($selectedArray);
                break;
            case '2' :
                $federations = ['data'=>$data,'selectedArray'=>$selected];
                $unions = $this->getUnions($selectedArray,0);
                $dcs = $this->getDcs($unions['selectedArray']);
                break;
        }
        return ['federation'=>$federations,'union'=>$unions,'dcs'=>$dcs];
    }

    private function getUnions($fedearionArray,$dcsArray){

        $query = TblUnions::find();
        $query->select(['union_code','union_name']);
        $query->where(['is_active' => 1]);
        $selected=[];
        $selectedValue = [];
        if (!empty(Yii::$app->session->get('Unions'))){
             $sel=explode(',',Yii::$app->session->get('Unions'));
             $query->andWhere(['union_code'=>$sel]);
        }
        if ($fedearionArray !== 0){
            $query->andWhere(['federation_code' => array_keys($fedearionArray)]);
            $list = $query->asArray()->all();
            $data=ArrayHelper::map($list,'union_code','union_name');
        }
        if($dcsArray!=0){

            $codes = TblDcs::find()->select(['union_code'])->where(['dcs_code' =>array_flip($dcsArray)])->asArray()->all();
            $query->andWhere(['union_code' => $codes]);
            $list = $query->asArray()->all();

            $data=ArrayHelper::map($list,'union_code','union_name');
            foreach ($data as $key => $row) {
                    $selected[$key] = ['selected'=>'selected'];
                    $selectedValue[$key] = $row;
            }
        }
        /*$selected = [];
        $selectedValue = [];
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($unions, 'union_code')) !== FALSE) {
                $selected[$key] = ['selected'=>'selected'];
                $selectedValue[$key] = $row;
            }
        }
//        $data=ArrayHelper::map($unions,'union_code','dcs_name');
//        $selectedValue=ArrayHelper::map($unions,'union_code',function($array,$key) {
//                 return ['selected' => 'selected'];
//        });*/
        return ['data'=>$data,'selectedArray'=>$selected,'selectedValue'=>$selectedValue];
    }

    private function getFederations($federationArray,$unionArray){

        $allFeder = User::getSelectedOrganization(2);
        $selected = [];
        $selectedValue = [];
        foreach ($allFeder['data'] as $row){
                $data[$row->{$allFeder['field'][0]}] = $row->{$allFeder['field'][1]};
        }
        $unions = TblUnions::find()->joinWith(['federationCode'])->select('tbl_federations.federation_code,tbl_federations.federation_name as union_name')->where(['union_code' => array_flip($unionArray)])->asArray()->all();
        foreach ($data as $key => $row) {
            if (array_search($key, array_column($unions, 'federation_code')) !== FALSE) {
                $selected[$key] = ['selected'=>'selected'];
                $selectedValue[$key] = $row;
            }
        }
        $selectedValue=ArrayHelper::map($unions,'federation_code',function($array,$key) {
                 return ['selected' => 'selected'];
        });
        return ['data'=>$data,'selectedArray'=>$selected,'selectedValue'=>$selectedValue];
    }

    private function getDcs($unions){
        $model = new TblDcs();
        $finalDcs = [];

        foreach ($unions as $key=>$row){
            $dcsList = $model->getDcsList($key);
            $finalDcs = array_merge($finalDcs,$dcsList);
        }
        return ['data'=>$finalDcs,'selectedArray'=>[]];
    }
    
    public function getCode(){
        
        $orgCode = Yii::$app->session->get('organizations_code');
        $len = strlen($orgCode);
        
        $val = (new \yii\db\Query)
                ->select(["MAX(convert(int,id)) as id"])
                ->from('tbl_user_organization_mapping')               
                ->one();
        $code1 = (int)$val['id'] + 1 ;

        $value = $orgCode.$code1;
        
        return $value;
    }
    
     public function getInstalltionCode($orgCode){
        
        $orgCode = Yii::$app->session->get('organizations_code');
        $len = strlen($orgCode);
        
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`id` FROM ".$len." +1)) AS UNSIGNED)) as id")
                ->from('tbl_user_organization_mapping')
                ->where('(CAST(trim(SUBSTRING(id, 1,'.$len.')) AS UNSIGNED))="'.trim($orgCode).'"')
                ->one();
        $code1 = (int)$val['id'] + 1 ;

        $value = $orgCode.$code1;
        
        return $value;
    }
    
    public function getUserOrgs($userCode){
        $records = $this->find()->select(['organization_code'])->where(['user_id'=>$userCode,'is_active'=>1])->asArray()->all();
        return $records;
    }
}
