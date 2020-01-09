<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblTransferType]].
 *
 * @see TblTransferType
 */
class TblTransferTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTransferType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTransferType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
