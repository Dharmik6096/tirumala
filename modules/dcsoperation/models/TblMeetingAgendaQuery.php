<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMeetingAgenda]].
 *
 * @see TblMeetingAgenda
 */
class TblMeetingAgendaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMeetingAgenda[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMeetingAgenda|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
