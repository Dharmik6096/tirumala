<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "masterfarmer".
 *
 * @property string $farmerid
 * @property string $farmername
 * @property string $farmergender
 * @property string $farmeraddress
 * @property string $farmercontact
 * @property integer $farmercow
 * @property integer $farmerbuff
 * @property string $farmerbank
 * @property string $farmerbankbranch
 * @property string $farmerbankac
 * @property string $farmerstatus
 * @property string $farmerremark
 * @property string $villageid
 * @property string $createdby
 * @property string $createddate
 * @property string $modifyby
 * @property string $modifydate
 * @property string $farmerid1
 * @property string $farmerpanno
 * @property string $farmeraadharcode
 * @property string $farmerErpcode
 * @property string $farmerbankifsccode
 * @property integer $data_post_status
 */
class MasterfarmerCreamy extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'masterfarmer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['farmerid', 'villageid'], 'required'],
            [['farmerid', 'farmername', 'farmergender', 'farmeraddress', 'farmercontact', 'farmerbank', 'farmerbankbranch', 'farmerbankac', 'farmerstatus', 'farmerremark', 'villageid', 'createdby', 'modifyby', 'farmerid1', 'farmerpanno', 'farmeraadharcode', 'farmerErpcode', 'farmerbankifsccode'], 'string'],
            [['farmercow', 'farmerbuff', 'data_post_status'], 'integer'],
            [['createddate', 'modifydate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'farmerid' => Yii::t('app', 'Farmerid'),
            'farmername' => Yii::t('app', 'Farmername'),
            'farmergender' => Yii::t('app', 'Farmergender'),
            'farmeraddress' => Yii::t('app', 'Farmeraddress'),
            'farmercontact' => Yii::t('app', 'Farmercontact'),
            'farmercow' => Yii::t('app', 'Farmercow'),
            'farmerbuff' => Yii::t('app', 'Farmerbuff'),
            'farmerbank' => Yii::t('app', 'Farmerbank'),
            'farmerbankbranch' => Yii::t('app', 'Farmerbankbranch'),
            'farmerbankac' => Yii::t('app', 'Farmerbankac'),
            'farmerstatus' => Yii::t('app', 'Farmerstatus'),
            'farmerremark' => Yii::t('app', 'Farmerremark'),
            'villageid' => Yii::t('app', 'Villageid'),
            'createdby' => Yii::t('app', 'Createdby'),
            'createddate' => Yii::t('app', 'Createddate'),
            'modifyby' => Yii::t('app', 'Modifyby'),
            'modifydate' => Yii::t('app', 'Modifydate'),
            'farmerid1' => Yii::t('app', 'Farmerid1'),
            'farmerpanno' => Yii::t('app', 'Farmerpanno'),
            'farmeraadharcode' => Yii::t('app', 'Farmeraadharcode'),
            'farmerErpcode' => Yii::t('app', 'Farmer Erpcode'),
            'farmerbankifsccode' => Yii::t('app', 'Farmerbankifsccode'),
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
        return $this->updateAll(['data_post_status' => 1], ['farmerid' => $farmer_id]);
    }

}
