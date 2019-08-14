<?php

namespace app\modules\webservice\eipl\models;

/**
 * This is the ActiveQuery class for [[TblEiplApiRequestLog]].
 *
 * @see TblEiplApiRequestLog
 */
class TblEiplApiRequestLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblEiplApiRequestLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblEiplApiRequestLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
