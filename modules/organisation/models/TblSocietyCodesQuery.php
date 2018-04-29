<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblSocietyCodes]].
 *
 * @see TblSocietyCodes
 */
class TblSocietyCodesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSocietyCodes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSocietyCodes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
