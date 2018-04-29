<?php

namespace app\modules\rmrd\models;

use Yii;

/**
 * This is the model class for table "Log".
 *
 * @property integer $Id
 * @property string $Date
 * @property string $Thread
 * @property string $Level
 * @property string $Logger
 * @property string $Message
 * @property string $Exception
 */
class Log extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_rmrd'); // rmrd database
    }

    public $dcs_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['Date', 'Thread', 'Level', 'Logger', 'Message'], 'required'],
            [['Date'], 'safe'],
            [['Thread', 'Level', 'Logger', 'Message', 'Exception'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'Id' => Yii::t('app', 'ID'),
            'Date' => Yii::t('app', 'Date'),
            'Thread' => Yii::t('app', 'Thread'),
            'Level' => Yii::t('app', 'Level'),
            'Logger' => Yii::t('app', 'Logger'),
            'Message' => Yii::t('app', 'Message'),
            'Exception' => Yii::t('app', 'Exception'),
        ];
    }

    /**
     * @inheritdoc
     * @return LogQuery the active query used by this AR class.
     */
    public static function find() {
        return new LogQuery(get_called_class());
    }

    public function getRecord() {
        return $this->find()->select(['SUBSTRING(Message, 26,12) As dcs_code', 'Date'])->where(['SUBSTRING(Message, 39,2)' => '07'])
                        ->andWhere(['between', 'date', date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])->distinct()->orderBy('Date DESC')->all();
    }

}
