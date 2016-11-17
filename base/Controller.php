<?php
namespace anda\cms\base;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class Controller extends \yii\web\Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
//				'only' => ['login', 'logout', 'signup'],
				'rules' => [
					[
						'allow' => true,
//						'actions' => ['logout'],
						'roles' => ['@'],
					],
				],
			]
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
				'view' => '@anda/cms/views/layouts/error',
			],
		];
	}
}
