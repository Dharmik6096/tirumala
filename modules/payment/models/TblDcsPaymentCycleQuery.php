<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblDcsPaymentCycle]].
 *
 * @see TblDcsPaymentCycle
 */
class TblDcsPaymentCycleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycle[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPaymentCycle|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
