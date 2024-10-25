<?php

namespace app\modules\organisation\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use Yii;
use yii\base\UserException;
use app\modules\syncutility\models\TblSentbox;

class TblMasterHierarchy extends ChildModel {
    public $operation, $ref_code;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_hierarchy';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['master_key','master_type','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','route_code','member_code','customer_code','ref_code1','ref_code2','ref_code3','ref_code4','ref_code5','is_active','wef_date','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type', 'operation'], 'safe'],
            [['union_code', 'master_key', 'master_type'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'master_hierarchy_code' => Yii::t('app', 'Master Hierarchy Code'),
            'master_key' => Yii::t('app', 'Master Key'),
            'master_type' => Yii::t('app', 'Master Type'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'PLANT'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route'),
            'member_code' => Yii::t('app', 'Member'),
            'customer_code' => Yii::t('app', 'Customer'),
            'ref_code1' => Yii::t('app', 'Ref Code 1'),
            'ref_code2' => Yii::t('app', 'Ref Code 2'),
            'ref_code3' => Yii::t('app', 'Ref Code 3'),
            'ref_code4' => Yii::t('app', 'Ref Code 4'),
            'ref_code5' => Yii::t('app', 'Ref Code 5'),
            'is_active' => Yii::t('app', 'Is Active'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col 1'),
            'x_col2' => Yii::t('app', 'X Col 2'),
            'x_col3' => Yii::t('app', 'X Col 3'),
            'x_col4' => Yii::t('app', 'X Col 4'),
            'x_col5' => Yii::t('app', 'X Col 5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getRouteMapping() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }
    
    public function getActiveCount($key_name, $key_reset_on){
        return $this->find()->where(['union_code' => $this->union_code, 'convert(bigint,'.$key_name.')' => (int) $this->{$key_name}])
            ->andWhere(['<>','master_hierarchy_code', $this->master_hierarchy_code])
            ->count();
        // return $this->find()->where(['union_code' => $this->union_code, $key_reset_on => $this->{$key_reset_on}, 'convert(bigint,'.$key_name.')' => (int) $this->{$key_name}])
    }

    public function afterSave($insert, $changedAttributes) {
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
        $sentboxArray = [];
        $types = [];
        if($this->master_type == 'tbl_plant'){
            $types = ['vlc', 'bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes($this->plant_code);
        } else if($this->master_type == 'tbl_mcc_plant'){
            $types = ['vlc', 'bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code);
        } else if($this->master_type == 'tbl_bmc'){
            $types = ['vlc', 'bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '', true); // map_bmc, own_bmc, vlc
        }   else if($this->master_type == 'tbl_dcs'){
            $types = ['vlc', 'bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        } else if($this->master_type == 'tbl_member'){
            $types = ['vlc', 'bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code); // parent vlc, parent bmc - is_bmc = true
        } else if($this->master_type == 'tbl_customer_master'){
            $types = ['bmc'];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '', FALSE);
        } else if($this->master_type == 'tbl_route_mapping'){
            $types = ['vlc', 'bmc'];
            if (!empty($this->dcs_code)) {
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
            } else if (!empty($this->bmc_code)) {
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code);
            }
        }
        foreach ($sentboxArray as $sent) {
            if (in_array(strtolower($sent['type']), $types)) {
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }
    
    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }
}
