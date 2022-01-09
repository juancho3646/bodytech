<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Bodytech laravel Documentation",
     *      description="Technical Test Application",
     *      @OA\Contact(
     *          email="juancho3646@gmail.com"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server in Localhost"
     * )

     *
     * @OA\Tag(
     *     name="Auth",
     *     description="API Endpoints of Register and Login"
     * )
     * @OA\Tag(
     *     name="Products",
     *     description="API Endpoints of Products"
     * )
     * @OA\Tag(
     *     name="Shopping Cart",
     *     description="API Endpoints of Shopping Cart"
     * )
     *
     * @OA\SecurityScheme(
     *       securityScheme="token",
     *       type="apiKey",
     *       name="Authorization",
     *       in="header",
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
