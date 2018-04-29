<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblTmpMemberPayment]].
 *
 * @see TblTmpMemberPayment
 */
class TblTmpMemberPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTmpMemberPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTmpMemberPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
