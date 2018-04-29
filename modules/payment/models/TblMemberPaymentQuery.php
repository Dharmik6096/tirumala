<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblMemberPayment]].
 *
 * @see TblMemberPayment
 */
class TblMemberPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMemberPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMemberPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
