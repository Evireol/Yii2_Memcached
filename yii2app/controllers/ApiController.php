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
        if (in_array($action->id, ['add-todo', 'list', 'view'])) {
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

    public function actionView($id)
{
    $task = Todo::getById($id);
    if ($task === null) {
        Yii::$app->response->statusCode = 404;
        return ['error' => 'Task not found'];
    }
    return $task;
}

    public function actionList()
    {
        return Todo::getAll();
    }
}