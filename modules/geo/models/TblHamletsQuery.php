<?php

namespace app\modules\geo\models;


/**
 * This is the ActiveQuery class for [[TblHamlets]].
 *
 * @see TblHamlets
 */
class TblHamletsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHamlets[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHamlets|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
