<?php
/**
 * @copyright 2018 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace Tests\Feature\Controllers;

use HoneyComb\Core\Http\Controllers\Admin\HCLanguageController;
use HoneyComb\Core\Http\Requests\Admin\HCLanguageRequest;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * Class HCLanguageControllerTest
 * @package Tests\Feature\Languages
 */
class HCLanguageControllerTest extends TestCase
{
    use RefreshDatabase, InteractsWithDatabase;

    /**
     * @test
     * @group lang
     */
    public function it_must_enable_front_end_language_by_patch_method(): void
    {
        $request = new HCLanguageRequest([], [], ['front_end' => 1]);
        $request->setMethod('PATCH');

        /** @var JsonResponse $response */
        $response = $this->getTestClassInstance()->patch($request, 'lt');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Updated', $response->getData()->message);
    }

    /**
     * @return HCLanguageController
     */
    private function getTestClassInstance(): HCLanguageController
    {
        return $this->app->make(HCLanguageController::class);
    }


}
