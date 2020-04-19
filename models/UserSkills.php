<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_skills".
 *
 * @property int $user_id
 * @property int $skill_id
 *
 * @property Skills $skill
 * @property Users $user
 */
class UserSkills extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'skill_id'], 'required'],
            [['user_id', 'skill_id'], 'integer'],
            [['user_id', 'skill_id'], 'unique', 'targetAttribute' => ['user_id', 'skill_id']],
            [['skill_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skills::className(), 'targetAttribute' => ['skill_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'skill_id' => 'Skill ID',
        ];
    }

    /**
     * Gets query for [[Skill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkill()
    {
        return $this->hasOne(Skills::className(), ['id' => 'skill_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
