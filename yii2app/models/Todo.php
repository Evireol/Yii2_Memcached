<?php
namespace app\models;

use Yii;

class Todo
{
    public static function add($text)
    {
        $file = Yii::getAlias('@app/runtime/todo.json');
        $todos = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $todos[] = [
            'id' => time(), // простой ID
            'text' => $text,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        file_put_contents($file, json_encode($todos, JSON_PRETTY_PRINT));
        return end($todos);
    }

    public static function getAll()
    {
        $file = Yii::getAlias('@app/runtime/todo.json');
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }
}