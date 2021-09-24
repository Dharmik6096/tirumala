<?php

namespace app\modules\eipldpu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\eipldpu\models\TblEiplPacketProcess;

/**
 * TblEiplPacketProcessSearch represents the model behind the search form about `app\modules\eipldpu\models\TblEiplPacketProcess`.
 */
class TblEiplPacketProcessSearch extends TblEiplPacketProcess {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['packet_id', 'is_decrypted', 'main_table', 'source_type', 'sampleno'], 'safe'],
            [['dcs_code', 'file_name', 'line_text', 'line_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'farmerid', 'farmername', 'farmermo', 'vlccid', 'mccid', 'txflag', 'dtdate', 'shift', 'milktype', 'qtymode', 'sampletime', 'response_msg'], 'safe'],
            [['qty', 'amt', 'rate', 'fat', 'snf', 'water'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = TblEiplPacketProcess::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'file_name' => $this->file_name,
        ]);



        return $dataProvider;
    }

}
