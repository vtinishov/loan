<?php

namespace app\controllers;

use OpenApi\Annotations as OA;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use app\models\LoanRequest;

class ProcessorController extends Controller
{
    /**
     * Обработка ожидающих заявок на кредит.
     *
     * @OA\Get(
     *     path="/processor",
     *     summary="Обработать ожидающие заявки",
     *     tags={"Процессор"},
     *     @OA\Parameter(
     *         name="delay",
     *         in="query",
     *         required=false,
     *         description="Задержка в секундах между обработкой заявок",
     *         @OA\Schema(type="integer", default=0)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заявки успешно обработаны",
     *         @OA\JsonContent(
     *             @OA\Property(property="result", type="boolean", example=true)
     *         )
     *     )
     * )
     *
     * @throws Exception
     */
    public function actionIndex($delay = 0): array
    {
        $delay = (int)$delay;

        $requests = LoanRequest::find()
            ->where(['status' => LoanRequest::STATUS_PENDING])
            ->all();

        foreach ($requests as $loan) {
            if ($delay > 0) {
                sleep($delay);
            }

            $this->processLoan($loan);
        }

        return ['result' => true];
    }

    /**
     * @throws Exception
     */
    private function processLoan(LoanRequest $loan)
    {
        $hasApproved = LoanRequest::find()
            ->where(['user_id' => $loan->user_id, 'status' => LoanRequest::STATUS_APPROVED])
            ->exists();

        if ($hasApproved) {
            $loan->status = LoanRequest::STATUS_DECLINED;
        } else {
            $loan->status = (rand(1, 100) <= 10)
                ? LoanRequest::STATUS_APPROVED
                : LoanRequest::STATUS_DECLINED;
        }

        $loan->save();
    }
}