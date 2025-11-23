<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Todo;

class ApiController extends Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['add-todo', 'list'])) {
            $this->enableCsrfValidation = false;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionAddTodo()
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            return ['error' => 'Only POST allowed'];
        }
        $text = trim($request->post('text'));
        if (empty($text)) {
            return ['error' => 'Field "text" is required'];
        }
        return [
            'success' => true,
            'task' => Todo::add($text),
        ];
    }

    public function actionList()
    {
        return Todo::getAll();
    }
}