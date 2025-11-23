<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Todo;
use yii\web\NotFoundHttpException;

class ApiController extends Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['add-todo', 'list', 'view', 'update-status', 'delete'])) {
            $this->enableCsrfValidation = false;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionAddTodo()
    {
        $todo = new Todo();
        $todo->load(Yii::$app->request->post(), '');
        if ($todo->validate()) {
            $todo->save();
            return ['success' => true, 'task' => $todo];
        }
        return ['success' => false, 'errors' => $todo->errors];
    }

    public function actionList()
    {
        return Todo::find()->asArray()->all();
    }

    public function actionView($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        return $todo;
    }

    public function actionUpdateStatus($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        $status = Yii::$app->request->post('status');
        if (!in_array($status, Todo::STATUSES)) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'Invalid status'];
        }
        $todo->status = $status;
        $todo->save(false); // false = без валидации (статус уже проверен)
        return ['success' => true, 'task' => $todo];
    }

    public function actionDelete($id)
    {
        $todo = Todo::findOne($id);
        if (!$todo) {
            throw new NotFoundHttpException('Task not found');
        }
        $todo->delete();
        return ['success' => true];
    }
}