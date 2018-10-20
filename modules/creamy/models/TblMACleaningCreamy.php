<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "tbl_MA_Cleaning".
 *
 * @property integer $id
 * @property string $BMCCode
 * @property string $PPCode
 * @property string $dtdate
 * @property string $shift
 * @property string $cleaningdatetime
 * @property integer $CleaningCycles
 * @property integer $Measuring
 * @property integer $counter
 * @property string $updatedby
 * @property string $updateddate
 * @property integer $data_post_status
 */
class TblMACleaningCreamy extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    public static function tableName() {
        return 'tbl_MA_Cleaning';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'cleaningdatetime', 'CleaningCycles', 'Measuring', 'counter', 'updatedby', 'updateddate'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'updatedby'], 'string'],
            [['dtdate', 'cleaningdatetime', 'updateddate'], 'safe'],
            [['CleaningCycles', 'Measuring', 'counter', 'data_post_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'BMCCode' => 'Bmccode',
            'PPCode' => 'Ppcode',
            'dtdate' => 'Dtdate',
            'shift' => 'Shift',
            'cleaningdatetime' => 'Cleaningdatetime',
            'CleaningCycles' => 'Cleaning Cycles',
            'Measuring' => 'Measuring',
            'counter' => 'Counter',
            'updatedby' => 'Updatedby',
            'updateddate' => 'Updateddate',
            'data_post_status' => 'Data Post Status',
        ];
    }

    public function getData($vlccid = []) {
        $date = date('Y-m-d H:i:s', strtotime('-3 hours'));
        $data1 = $this->find()
                ->where(['or', ['data_post_status' => 0], ['data_post_status' => NULL]])
                ->andWhere(['PPCode' => $vlccid])
//                ->andWhere(['>=', 'dtdate', '2018-07-20 13:00:00'])
                ->limit(80)
//                ->orderby('dtdate ASC')
                ->all();
        $data2 = $this->find()
                ->where(['data_post_status' => 3])
                ->andWhere(['PPCode' => $vlccid])
//                ->andWhere(['<=', 'modifieddate', $date])
                ->limit(20)
//                ->orderby('dtdate ASC')
                ->all();
        $result = array_merge($data1, $data2);
        return $result;
    }

    public function updateData($id) {
        return $this->updateAll(['data_post_status' => 1], [ 'id' => $id]);
    }

}
