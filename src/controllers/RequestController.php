<?php

namespace app\controllers;

use OpenApi\Annotations as OA;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use app\models\LoanRequest;

/**
 * Class RequestController
 */
class RequestController extends Controller
{
    /**
     * Создание заявки на кредит.
     * Возвращает 400, если у пользователя уже есть одобренная заявка.
     *
     * @OA\Post(
     *     path="/requests",
     *     tags={"Заявки"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "amount", "term"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="amount", type="integer", example=10000),
     *             @OA\Property(property="term", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Заявка создана",
     *         @OA\JsonContent(
     *             @OA\Property(property="result", type="boolean", example=true),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=400, description="Ошибка: уже есть одобренная заявка или невалидные данные",
     *         @OA\JsonContent(
     *             @OA\Property(property="result", type="boolean", example=false)
     *         )
     *     )
     * )
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