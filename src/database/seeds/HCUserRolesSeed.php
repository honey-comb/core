<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Core\Database\Seeds;

use Illuminate\Database\Seeder;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;

/**
 * Class HCUserRolesSeed
 * @package HoneyComb\Core\Database\Seeds
 */
class HCUserRolesSeed extends Seeder
{
    /**
     * @var HCRoleRepository
     */
    private $repository;

    /**
     * HCUserRolesSeed constructor.
     * @param HCRoleRepository $repository
     */
    public function __construct(HCRoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        // http://stackoverflow.com/q/1598411
        $list = [
            ['name' => 'Super Admin', 'slug' => HCRoleRepository::ROLE_SA], // Manage everything
            ['name' => 'Project Admin', 'slug' => HCRoleRepository::ROLE_PA], // Manage most aspects of the site
            ['name' => 'User', 'slug' => HCRoleRepository::ROLE_U], // Average Joe
        ];

        foreach ($list as $roleData) {
            $role = $this->repository->findOneBy(['slug' => $roleData['slug']]);

            if (!$role) {
                $this->repository->create($roleData);
            }
        }
    }
}
