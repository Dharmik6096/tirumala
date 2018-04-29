<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblPurchaseRateBasedHistory]].
 *
 * @see TblPurchaseRateBasedHistory
 */
class TblPurchaseRateBasedHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPurchaseRateBasedHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateBasedHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
