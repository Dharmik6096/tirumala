<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblMasterTransfer]].
 *
 * @see TblMasterTransfer
 */
class TblMasterTransferQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMasterTransfer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMasterTransfer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
