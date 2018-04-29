<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hardwareconfigutation\models\TblInterfacingDevice;

/**
 * TblInterfacingDeviceSearch represents the model behind the search form about `app\modules\hardwareconfigutation\models\TblInterfacingDevice`.
 */
class TblInterfacingDeviceSearch extends TblInterfacingDevice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_code', 'baud_rate','bit_rate', 'device_type', 'incoming_data_type', 'length', 'parity', 'reading_type', 'stop_bit', 'device_manufacturer_id', 'created_at', 'created_by', 'deleted_at', 'deleted_by', 'device_name', 'discard_char', 'end_char', 'flg_sentbox_entry', 'reg_expression', 'split_char', 'start_char', 'sync_status', 'sync_timestamp', 'tare', 'updated_at', 'updated_by', 'union_code'], 'safe'],
            //[['bit_rate', 'device_type', 'incoming_data_type', 'length', 'parity', 'reading_type', 'stop_bit', 'device_manufacturer_id'], 'integer'],
            [['is_active', 'is_delete', 'is_snf'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblInterfacingDevice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['deviceManufacturer']);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'bit_rate' => $this->bit_rate,
            'device_type' => $this->device_type,
            'tbl_interfacing_device.is_active' => $this->is_active,
            'tbl_interfacing_device.is_delete' => 0,
            'is_snf' => $this->is_snf,
            'parity' => $this->parity,
            'reading_type' => $this->reading_type,
            'stop_bit' => $this->stop_bit,
        ]);

        $query->andFilterWhere(['like', 'device_code', $this->device_code])
            ->andFilterWhere(['like', 'baud_rate', $this->baud_rate])
            ->andFilterWhere(['like', 'incoming_data_type', $this->incoming_data_type])
            ->andFilterWhere(['like', 'device_name', $this->device_name])
            ->andFilterWhere(['like', 'discard_char', $this->discard_char])
            ->andFilterWhere(['like', 'end_char', $this->end_char])
            ->andFilterWhere(['like', 'tbl_device_manufacturer.manufacturer', $this->device_manufacturer_id])
            ->andFilterWhere(['like', 'reg_expression', $this->reg_expression])
            ->andFilterWhere(['like', 'split_char', $this->split_char])
            ->andFilterWhere(['like', 'start_char', $this->start_char])
            ->andFilterWhere(['like', 'length', $this->length])
            ->andFilterWhere(['like', 'tare', $this->tare])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }
}
