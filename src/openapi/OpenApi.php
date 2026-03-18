<?php

namespace app\openapi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     openapi="3.0.0",
 *     info={
 *         @OA\Info(
 *             title="Loan API",
 *             version="1.0.0",
 *             description="API для управления заявками на кредит"
 *         )
 *     },
 *     servers={
 *         @OA\Server(url="/", description="API Server")
 *     }
 * )
 */
class OpenApi
{
}
