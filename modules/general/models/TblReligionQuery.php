<?php

namespace app\modules\general\models;

/**
 * This is the ActiveQuery class for [[TblReligion]].
 *
 * @see TblReligion
 */
class TblReligionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblReligion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblReligion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
