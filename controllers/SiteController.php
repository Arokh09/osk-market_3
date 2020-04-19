<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Users;
use app\models\City;
use app\models\Skills;
use app\models\UserSkills;
use yii\web\HttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
										'add-user' => ['post'],
										'remove-user' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }




		public function actionIndex()
    {
			$this -> layout = 'clean';

			$users = Users::find()
			-> with('city')
			-> with('skills')
			-> all();
			return $this->render('index', ['users' => $users]);
		}




    public function actionGetUsers()
    {
			$users = Users::find()
			-> with('city')
			-> with('skills')
			-> asArray()
			-> all();

			foreach($users as &$user){
				$user['skills'] = array_map(
					function($arr){
						return $arr['name'];
					},
					$user['skills']
				);
			}

			return json_encode(['data' => $users], JSON_UNESCAPED_UNICODE);
		}




		private function getNewUserName(){
			if(!file_exists('name_rus.txt')) return;
			$filenames = file('name_rus.txt');

			return trim($filenames[rand(0, count($filenames)-1)]);
		}




		private function getRandomCityId(){
			return City::find()
			-> select('id')
			-> orderBy('RAND()')
			-> limit(1)
			-> asArray()
			-> one()['id'];
		}




		private function getRandomSkillIds($count){
			return Skills::find()
			-> select('id')
			-> orderBy('RAND()')
			-> limit($count)
			-> asArray()
			-> all();
		}




		private function addUserSkill($user_id, $skill_id){
			$userSkill = new UserSkills();
			$userSkill -> user_id = $user_id;
			$userSkill -> skill_id = $skill_id;
			if($userSkill -> save()){
				return true;
			}
			else return false;
		}




		public function actionAddUser(){
			$userName = $this -> getNewUserName();
			$randomCityId = $this -> getRandomCityId();

			if(!$userName || !$randomCityId){
				throw new HttpException(500);
			}

			$user = new Users();
			$user -> name = $userName;
			$user -> city_id = $randomCityId;
			if(!$user -> save()){
				throw new HttpException(500, json_encode($user -> errors));
			}

			foreach($this -> getRandomSkillIds(rand(1, 3)) as $skill){
				if(!$this -> addUserSkill($user -> id, $skill['id'])){
					throw new HttpException(500);
				}
			}

			return $userName;
		}




		public function actionRemoveUser(){
			$user_id = Yii::$app -> request -> getBodyParam('id');

			$user = Users::findOne($user_id);

			if(!$user || !$user -> delete()){
				throw new HttpException(500, json_encode($user -> errors));
			}
		}

}
