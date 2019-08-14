<?php

namespace app\modules\webservice\eipl\models;

/**
 * This is the ActiveQuery class for [[TblEiplAppLogin]].
 *
 * @see TblEiplAppLogin
 */
class TblEiplAppLoginQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblEiplAppLogin[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblEiplAppLogin|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
