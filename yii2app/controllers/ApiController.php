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
        $this->enableCsrfValidation = false;

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

        $task = Todo::add($text);

        return [
            'success' => true,
            'task' => $task,
        ];
    }

    // Опционально: получить список задач
    public function actionList()
    {
        return Todo::getAll();
    }
}