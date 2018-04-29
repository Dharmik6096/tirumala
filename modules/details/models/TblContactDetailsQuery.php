<?php

namespace app\modules\details\models;

/**
 * This is the ActiveQuery class for [[TblContactDetails]].
 *
 * @see TblContactDetails
 */
class TblContactDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblContactDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblContactDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
