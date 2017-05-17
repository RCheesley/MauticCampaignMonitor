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

use \CS_REST_Clients;
use \CS_REST_General;
use \CS_REST_Lists;

class CampaignMonitorService
{

    private $apiKey;

    /**
     * CampaignMonitorService constructor.
     * @param string $apiKey
     */
    function __construct(string $apiKey)
    {

        $this->apiKey = array('api_key' => $apiKey);

    }

    /**
     * Creates a CampaignMonitor API object
     * @param string $apiType
     * @param int $objectId
     * @return CS_REST_Clients|CS_REST_General|CS_REST_Lists
     */
    public function createCampaignMonitorApi(string $apiType, $objectId = 0)
    {

        switch ($apiType) {

            case 'general':
                $api = new CS_REST_General($this->apiKey);
                break;
            case 'clients':
                $api = new CS_REST_Clients($objectId, $this->apiKey);
                break;
            case 'lists':
                $api = new CS_REST_Lists($objectId, $this->apiKey);
                break;
            default:
                $api = new CS_REST_General($this->apiKey);
        }

        return $api;

    }


}