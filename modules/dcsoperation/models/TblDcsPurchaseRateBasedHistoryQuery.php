<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateBasedHistory]].
 *
 * @see TblDcsPurchaseRateBasedHistory
 */
class TblDcsPurchaseRateBasedHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateBasedHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateBasedHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
