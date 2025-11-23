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
        if (in_array($action->id, ['add-todo', 'list', 'view', 'update-status'])) {
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

    public function actionUpdateStatus($id)
    {
        $request = Yii::$app->request;
        if (!$request->isPost) {
            Yii::$app->response->statusCode = 405;
            return ['error' => 'Only POST allowed'];
        }
    
        // Пустая строка, если null
        $status = $request->post('status', '');
    
        if (empty(trim($status))) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Field "status" is required'];
        }
    
        $status = trim($status);
    
        $result = Todo::updateStatus($id, $status);
        if (isset($result['error'])) {
            Yii::$app->response->statusCode = 400;
            return $result;
        }
    
        return $result;
    }

    public function actionList()
    {
        return Todo::getAll();
    }
}