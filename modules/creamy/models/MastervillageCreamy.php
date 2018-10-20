<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "mastervillage".
 *
 * @property string $villageid
 * @property string $villagename
 * @property string $villageagentname
 * @property string $villageagentaddress
 * @property string $villageagentcontact
 * @property integer $villageagentcommission
 * @property string $villageagentbank
 * @property string $villageagentbankbranch
 * @property string $villageagentBranchCode
 * @property string $villagebankifsccode
 * @property string $villageagentbankac
 * @property string $routeid
 * @property string $mccid
 * @property string $villageopendate
 * @property string $villageclosedate
 * @property string $villagestatus
 * @property string $villagerateid
 * @property string $createdby
 * @property string $createddate
 * @property string $modifyby
 * @property string $modifydate
 * @property string $beneficiaryname
 * @property string $type
 * @property integer $villagetranscomm
 * @property integer $chcharges
 * @property integer $extrainc
 * @property integer $schemeid
 * @property string $Taluka_Code
 * @property string $District_Code
 * @property string $PAN
 * @property string $ChequeNo
 * @property string $ChequeType
 * @property string $JoinDate
 * @property string $ReferenceCode
 * @property string $AadharNo
 * @property integer $NameRequest
 * @property integer $RateBlock
 * @property integer $rateflag
 * @property string $DPUVersionNo
 * @property integer $data_post_status
 */
class MastervillageCreamy extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mastervillage';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['villageid'], 'required'],
            [['villageid', 'villagename', 'villageagentname', 'villageagentaddress', 'villageagentcontact', 'villageagentbank', 'villageagentbankbranch', 'villageagentBranchCode', 'villagebankifsccode', 'villageagentbankac', 'routeid', 'mccid', 'villagestatus', 'villagerateid', 'createdby', 'modifyby', 'beneficiaryname', 'type', 'Taluka_Code', 'District_Code', 'PAN', 'ChequeNo', 'ChequeType', 'ReferenceCode', 'AadharNo', 'DPUVersionNo'], 'string'],
            [['villageagentcommission', 'villagetranscomm', 'chcharges', 'extrainc', 'schemeid', 'NameRequest', 'RateBlock', 'rateflag', 'data_post_status'], 'integer'],
            [['villageopendate', 'villageclosedate', 'createddate', 'modifydate', 'JoinDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'villageid' => Yii::t('app', 'Villageid'),
            'villagename' => Yii::t('app', 'Villagename'),
            'villageagentname' => Yii::t('app', 'Villageagentname'),
            'villageagentaddress' => Yii::t('app', 'Villageagentaddress'),
            'villageagentcontact' => Yii::t('app', 'Villageagentcontact'),
            'villageagentcommission' => Yii::t('app', 'Villageagentcommission'),
            'villageagentbank' => Yii::t('app', 'Villageagentbank'),
            'villageagentbankbranch' => Yii::t('app', 'Villageagentbankbranch'),
            'villageagentBranchCode' => Yii::t('app', 'Villageagent Branch Code'),
            'villagebankifsccode' => Yii::t('app', 'Villagebankifsccode'),
            'villageagentbankac' => Yii::t('app', 'Villageagentbankac'),
            'routeid' => Yii::t('app', 'Routeid'),
            'mccid' => Yii::t('app', 'Mccid'),
            'villageopendate' => Yii::t('app', 'Villageopendate'),
            'villageclosedate' => Yii::t('app', 'Villageclosedate'),
            'villagestatus' => Yii::t('app', 'Villagestatus'),
            'villagerateid' => Yii::t('app', 'Villagerateid'),
            'createdby' => Yii::t('app', 'Createdby'),
            'createddate' => Yii::t('app', 'Createddate'),
            'modifyby' => Yii::t('app', 'Modifyby'),
            'modifydate' => Yii::t('app', 'Modifydate'),
            'beneficiaryname' => Yii::t('app', 'Beneficiaryname'),
            'type' => Yii::t('app', 'Type'),
            'villagetranscomm' => Yii::t('app', 'Villagetranscomm'),
            'chcharges' => Yii::t('app', 'Chcharges'),
            'extrainc' => Yii::t('app', 'Extrainc'),
            'schemeid' => Yii::t('app', 'Schemeid'),
            'Taluka_Code' => Yii::t('app', 'Taluka  Code'),
            'District_Code' => Yii::t('app', 'District  Code'),
            'PAN' => Yii::t('app', 'Pan'),
            'ChequeNo' => Yii::t('app', 'Cheque No'),
            'ChequeType' => Yii::t('app', 'Cheque Type'),
            'JoinDate' => Yii::t('app', 'Join Date'),
            'ReferenceCode' => Yii::t('app', 'Reference Code'),
            'AadharNo' => Yii::t('app', 'Aadhar No'),
            'NameRequest' => Yii::t('app', 'Name Request'),
            'RateBlock' => Yii::t('app', 'Rate Block'),
            'rateflag' => Yii::t('app', 'Rateflag'),
            'DPUVersionNo' => Yii::t('app', 'Dpuversion No'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
        ];
    }

    public function getData($vlccid = []) {
        $date = date('Y-m-d H:i:s', strtotime('-3 hours'));
        $data1 = $this->find()
                ->where(['or', ['data_post_status' => 0], ['data_post_status' => NULL]])
                ->andWhere(['villageid' => $vlccid])
//                ->andWhere(['>=', 'dtdate', '2018-07-20 13:00:00'])
                ->limit(80)
//                ->orderby('dtdate ASC')
                ->all();
        $data2 = $this->find()
                ->where(['data_post_status' => 3])
                ->andWhere(['villageid' => $vlccid])
//                ->andWhere(['<=', 'modifieddate', $date])
                ->limit(20)
//                ->orderby('dtdate ASC')
                ->all();
        $result = array_merge($data1, $data2);
        return $result;
    }

    public function updateData($farmer_id) {
        return $this->updateAll(['data_post_status' => 1], ['villageid' => $farmer_id]);
    }

}
