<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblTankerRateBasedHistory]].
 *
 * @see TblTankerRateBasedHistory
 */
class TblTankerRateBasedHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTankerRateBasedHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateBasedHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
