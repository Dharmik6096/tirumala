<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateDetailsHistory]].
 *
 * @see TblDcsPurchaseRateDetailsHistory
 */
class TblDcsPurchaseRateDetailsHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateDetailsHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateDetailsHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
