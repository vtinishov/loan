<?php

namespace app\controllers;

use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use app\models\LoanRequest;

class RequestController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $request = Yii::$app->request;
        $model = new LoanRequest();
        $model->attributes = $request->post();

        $hasApproved = LoanRequest::find()
            ->where(['user_id' => $model->user_id, 'status' => LoanRequest::STATUS_APPROVED])
            ->exists();

        if (!$hasApproved && $model->save()) {
            Yii::$app->response->statusCode = 201;
            return [
                'result' => true,
                'id' => $model->id,
            ];
        }

        Yii::$app->response->statusCode = 400;
        return ['result' => false];
    }
}