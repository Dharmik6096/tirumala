<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblProcessedFiles;

/**
 * TblProcessedFilesSearch represents the model behind the search form about `app\modules\collection\models\TblProcessedFiles`.
 */
class TblProcessedFilesSearch extends TblProcessedFiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['process_id'], 'integer'],
            [['cp_code', 'file_path', 'vendor_id', 'processed_at', 'union_code', 'dcs_code'], 'safe'],
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
        $query = TblProcessedFiles::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        Yii::$app->general->filterByOrg($query,$this);
        
        if(!empty($request['min_date']) && !empty($request['max_date'])) 
        { 
            $start_date=date('Y-m-d',  strtotime($request['min_date']));
            $end_date=date('Y-m-d',  strtotime($request['max_date']));
            if($start_date!=$end_date)
                $query->andFilterWhere(['between', 'CAST(processed_at AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), processed_at, 126)', $start_date]);                
        }
        if (!empty($this->processed_at))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), processed_at, 126)', date('Y-m-d', strtotime($this->processed_at))]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'process_id' => $this->process_id,
//            'processed_at' => $this->processed_at,
        ]);

        $query->andFilterWhere(['like', 'cp_code', $this->cp_code])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'vendor_id', $this->vendor_id]);

        return $dataProvider;
    }
}
