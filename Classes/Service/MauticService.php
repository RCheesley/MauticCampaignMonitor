<?php
declare(strict_types = 1);
/**
 *
 * PHP Version 7.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Woeler\MauticCampaignMonitor
 * @author     Woeler <woeler@esoleaderboards.com>
 * @copyright  2017 Woeler
 * @license    http://www.gnu.org/licenses/  GNU General Public License 3
 * @version    0.1.0
 *
 */

namespace Woeler\MauticCampaignMonitor\Service;

use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;


class MauticService
{

    private $mauticUsername;
    private $mauticPassword;
    private $mauticUrl;

    /**
     * MauticService constructor.
     * @param string $mauticUsername
     * @param string $mauticPassword
     * @param string $mauticUrl
     */
    function __construct(string $mauticUsername, string $mauticPassword, string $mauticUrl)
    {
        $this->mauticUsername = $mauticUsername;
        $this->mauticPassword = $mauticPassword;
        $this->mauticUrl = $mauticUrl;
    }


    /**
     * @return \Mautic\Auth\AuthInterface
     */
    public function mauticAuthorization(): \Mautic\Auth\AuthInterface
    {
        // Create the authorization array
        $settings = array(
            'userName' => $this->mauticUsername,
            'password' => $this->mauticPassword,
        );

        // Initiate the auth object specifying to use BasicAuth
        $initAuth = new ApiAuth();
        $auth = $initAuth->newAuth($settings, 'BasicAuth');

        // Return the authorization object
        return $auth;
    }


    /**
     * @param string $apiType
     * @param \Mautic\Auth\AuthInterface $auth
     * @return \Mautic\Api\Api
     */
    public function createMauticApi(string $apiType, \Mautic\Auth\AuthInterface $auth): \Mautic\Api\Api
    {
        // Instantiate the api object
        $api = new MauticApi();
        return $api->newApi($apiType, $auth, $this->mauticUrl);
    }


}