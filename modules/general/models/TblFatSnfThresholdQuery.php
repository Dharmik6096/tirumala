<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblFatSnfThreshold]].
 *
 * @see TblFatSnfThreshold
 */
class TblFatSnfThresholdQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFatSnfThreshold[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFatSnfThreshold|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
