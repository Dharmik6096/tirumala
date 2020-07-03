<?php

namespace app\modules\eipldpu\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_eipl_packet_process".
 *
 * @property integer $packet_id
 * @property string $dcs_code
 * @property string $file_name
 * @property string $line_text
 * @property string $line_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_decrypted
 * @property integer $main_table
 * @property integer $source_type
 * @property string $farmerid
 * @property string $farmername
 * @property string $farmermo
 * @property string $vlccid
 * @property string $mccid
 * @property integer $sampleno
 * @property string $txflag
 * @property string $qty
 * @property string $amt
 * @property string $rate
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $dtdate
 * @property string $shift
 * @property string $milktype
 * @property string $qtymode
 * @property string $sampletime
 */
class TblEiplPacketProcess extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_packet_process';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['qtymode'], 'default', 'value' => 'L'],
            [['dcs_code', 'file_name', 'line_text', 'line_no', 'created_by', 'updated_by', 'farmerid', 'farmername', 'farmermo', 'vlccid', 'mccid', 'txflag', 'shift', 'milktype', 'qtymode'], 'safe'],
            [['created_at', 'updated_at', 'dtdate', 'sampletime'], 'safe'],
            [['is_decrypted', 'main_table', 'source_type', 'sampleno'], 'safe'],
            [['qty', 'amt', 'rate', 'fat', 'snf', 'water'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'packet_id' => Yii::t('app', 'Packet ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'file_name' => Yii::t('app', 'FILE'),
            'line_text' => Yii::t('app', 'Line Text'),
            'line_no' => Yii::t('app', 'Line'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_decrypted' => Yii::t('app', 'Is Decrypted'),
            'main_table' => Yii::t('app', 'Main Table'),
            'source_type' => Yii::t('app', 'Source Type'),
            'farmerid' => Yii::t('app', 'Member Code'),
            'farmername' => Yii::t('app', 'M.Name'),
            'farmermo' => Yii::t('app', 'Farmermo'),
            'vlccid' => Yii::t('app', 'DCS Code'),
            'mccid' => Yii::t('app', 'Mccid'),
            'sampleno' => Yii::t('app', 'Sample No.'),
            'txflag' => Yii::t('app', 'FLAG'),
            'qty' => Yii::t('app', 'QTY'),
            'amt' => Yii::t('app', 'AMOUNT'),
            'rate' => Yii::t('app', 'RTPL'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'water' => Yii::t('app', 'Water'),
            'dtdate' => Yii::t('app', 'DATE'),
            'shift' => Yii::t('app', 'SHIFT'),
            'milktype' => Yii::t('app', 'Milk Type'),
            'qtymode' => Yii::t('app', 'Qty Mode'),
            'sampletime' => Yii::t('app', 'TIME'),
            'response_msg' => Yii::t('app', 'Remarks'),
        ];
    }

    public function getFileData() {
        $query = $this->find()->where(['file_name' => $this->file_name])->orderBy(['CAST(file_name as int)' => SORT_ASC, 'CAST(line_no as int)' => SORT_ASC]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
    }

    public function getFileName() {
        return $this->hasOne(TblEiplPacketFileLog::className(), ['file_id' => 'file_name']);
    }

}
