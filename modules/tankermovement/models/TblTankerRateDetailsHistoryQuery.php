<?php

namespace app\modules\tankermovement\models;

/**
 * This is the ActiveQuery class for [[TblTankerRateDetailsHistory]].
 *
 * @see TblTankerRateDetailsHistory
 */
class TblTankerRateDetailsHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTankerRateDetailsHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateDetailsHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
