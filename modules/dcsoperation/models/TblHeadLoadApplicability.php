<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblRoutes;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_head_load_applicability".
 *
 * @property string $code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $head_load_code
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblHeadLoadApplicability extends \app\models\ChildModel
{
    public $route_code;
    public $organization;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wef_date','route_code','organization','dcs_code'], 'required'],
            [['created_at','is_delete', 'deleted_at', 'dcs_code', 'updated_at', 'organization', 'wef_date','route_code'], 'safe'],
//            [['is_delete'], 'integer'],
            [['code', 'head_load_code'], 'string', 'max' => 20],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
         ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'route_code' => Yii::t('app', 'Route'),
            'created_by' => Yii::t('app', 'Created By'),
            'organization'=> Yii::t('app', 'Organization'),
            'dcs_code' => Yii::t('app', 'Organization'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'head_load_code' => Yii::t('app', 'Head Load'),
            'sub_center_code' => Yii::t('app', 'Sub Center'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode()
    {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicabilityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadApplicabilityQuery(get_called_class());
    }
    
    public function getCode(){
        $val= (new \yii\db\Query)
                ->select("MAX(CAST(trim(code) AS UNSIGNED)) as code")
                ->from('tbl_head_load_applicability')
                ->one();
        $number=(int)$val['code']+1;
        return str_pad($number,2,'0',STR_PAD_LEFT);
    }
    
    /**
     * Return selected dcs/subcenter/route array in dcs head load mapping
     * @param type $headLoadCode
     * @param type $unionCode
     * @return type
     */
    public function getHeadLoadApplicability($headLoadCode,$unionCode){
        
        $routes = new TblRoutes;
        $routes = $routes->getRoutes($unionCode);
        
        $selected = $this->find()->joinWith(['subCenterCode','dcsCode'])->select('wef_date,tbl_head_load_applicability.dcs_code,tbl_head_load_applicability.sub_center_code')->where(['head_load_code'=>$headLoadCode])->all();
        $organizations = [];
        $selectedRoute = [];
        $selectedOrg = [];
        $orgFlag = 0;
        $wefDate = date('d-m-Y');
        foreach ($selected as $row){
            if(!empty($row->dcs_code)){
                $routeCode = $row->dcsCode->route_code;
                $orgFlag = 0;
                $selectedOrg[$row->dcs_code] = ['selected'=>'selected'];
            }else{
                $routeCode = $row->subCenterCode->route_code;
                $orgFlag =1;
                $selectedOrg[$row->sub_center_code] = ['selected'=>'selected'];
            }
            $wefDate = $row->wef_date;
            $selectedRoute[$routeCode] = ['selected'=>'selected'];
            
            $returnArray= $this->getAllOrg($routeCode,$orgFlag);
            $organizations = array_merge($organizations,$returnArray);
        }
        return ['routes'=>$routes,'orgFlag'=>$orgFlag,'wefDate'=>$wefDate,'selectedRoutes'=>$selectedRoute,'selectedOrganization'=>$selectedOrg,'selectedAllOrg'=>$organizations];
    }
    
    private function getAllOrg($routeCode,$orgFlag){
        $finalArray = [];
        if($orgFlag==0){
            $dcs = new TblDcs();
            $dcsAry = $dcs->getRouteDcs($routeCode);
            $records= ArrayHelper::map($dcsAry, 'dcs_code', 'dcs_name');
            $finalArray = array_merge($finalArray, $records);
        }else{
            $subCenter = new TblSubCenter();
            $dcsAry = $subCenter->getRouteSubcenter($routeCode);
            $records= ArrayHelper::map($dcsAry, 'sub_center_code', 'sub_center_name');
            $finalArray = array_merge($finalArray, $records);
        }
        
        return $finalArray;
    }
    
    public function checkDuplicate(){
        $check = $this->find()->where(['wef_date'=> $this->wef_date,'dcs_code'=> $this->dcs_code])->count();
        
//        echo $this->wef_date.'- '.$this->dcs_code.'<br>';
        return $check;
    }
}
