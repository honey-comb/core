<?php
/**
 * @copyright 2019 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Exceptions\Traits;

use Exception;
use HoneyComb\Starter\Helpers\HCResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

/**
 * Trait HCExceptionHandlerTrait
 * @package HoneyComb\Core\Exceptions\Traits
 */
trait HCExceptionHandlerTrait
{
    /**
     * @param $request
     * @param AuthenticationException $exception
     * @return JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception): JsonResponse
    {
        return app(HCResponse::class)
            ->error(trans('HCCore::core.error.unauthenticated'), [], null, JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return app(HCResponse::class)
            ->error(trans('HCCore::core.error.invalid_data'), $exception->errors(), null, $exception->status);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param Exception $exception
     * @return array
     */
    protected function convertExceptionToArray(Exception $exception): array
    {
        $errors = [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];

        return [
            'message' => $exception->getMessage() ?: 'Server error',
            'errors' => config('app.debug') ? $errors : [],
        ];
    }
}
