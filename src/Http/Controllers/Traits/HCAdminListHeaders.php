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

namespace HoneyComb\Core\Http\Controllers\Traits;

/**
 * Trait HCAdminListHeaders
 * @package HoneyComb\Core\Http\Controllers\Traits
 */
trait HCAdminListHeaders
{
    /**
     * Admin header text type
     *
     * @param string $label
     * @return array
     */
    protected function headerText(string $label): array
    {
        return [
            'type' => 'text',
            'label' => $label,
        ];
    }

    /**
     * Admin header text type as html
     *
     * @param string $label
     * @return array
     */
    protected function headerHtml(string $label): array
    {
        return [
            'type' => 'html',
            'label' => $label,
        ];
    }

    /**
     * Admin header checkBox type
     *
     * @param string $label
     * @return array
     */
    protected function headerCheckBox(string $label): array
    {
        return [
            'type' => 'checkBox',
            'label' => $label,
        ];
    }

    /**
     * @param string $label
     * @param int $width
     * @param int $height
     * @return array
     */
    protected function headerImage(string $label, int $width = 100, int $height = 100): array
    {
        return $config = [
            'type' => 'image',
            'label' => $label,
            'width' => $width,
            'height' => $height,
            'hideDelete' => true,
        ];
    }

    /**
     * @param string $label
     * @param bool $useId
     * @param bool $external
     * @param string $url
     * @return array
     */
    protected function headerUrl(string $label, bool $external = true, string $url = '', bool $useId = true)
    {
        return [
            'type' => 'url',
            'label' => $label,
            'external' => $external,
            'url' => $url,
            //will be ignored only if url is empty
            'useId' => $useId,
        ];
    }

    /**
     * @param string $label
     * @param string|array $valuePath
     * @param string $addMore
     * @param string $idAs
     * @return array
     */
    protected function headerList(string $label, $valuePath, string $addMore = null, string $idAs = null): array
    {
        return [
            'type' => 'list',
            'label' => $label,
            'valuePath' => $valuePath,
            'addMore' => $addMore,
            'idAs' => $idAs,
        ];
    }

    /**
     * @param string $label
     * @param string $valuePath
     * @param string $prefix
     * @return array
     */
    protected function headerCopy(string $label, string $valuePath = null, string $prefix = null): array
    {
        return [
            'type' => 'copy',
            'label' => $label,
            'valuePath' => $valuePath,
            'prefix' => $prefix,
        ];
    }

    /**
     * @param string $label
     * @param int $utc
     * @return array
     */
    protected function headerTime(string $label, int $utc = 0): array
    {
        return [
            'type' => 'time',
            'label' => $label,
            'utc' => $utc,
        ];
    }

    /**
     * @param string $label
     * @return array
     */
    protected function headerDate(string $label): array
    {
        return [
            'type' => 'date',
            'label' => $label,
        ];
    }

    /**
     * @param string $label
     * @param string $url
     * @param string $icon
     * @return array
     */
    protected function headerAction(string $label, string $url, string $icon)
    {
        return [
            'type' => 'action',
            'label' => $label,
            'url' => $url,
            'icon' => $icon,
        ];
    }
}
