<?php
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
require_once __DIR__ . '/vendor/autoload.php';

use Woeler\MauticCampaignMonitor\Synchronizer;

/**
 * Before using this script make sure that Basic Auth is enabled in your Mautic instance
 * Always make sure your website is using a safe connection (SSL/HTTPS)
 *
 * Configure your settings here and then execute this file to send
 * your CampaignMonitor contacts to your Mautic installation
 *
 */
$config = array(
    'campaignMonitorApiKey' => '',
    'mauticUsername' => '',
    'mauticPassword' => '',
    'mauticUrl' => '',
);

// New Synchronizer
$sync = new Synchronizer($config['campaignMonitorApiKey'], $config['mauticUsername'], $config['mauticPassword'], $config['mauticUrl']);

// Do the work
$result = $sync->execute();

// Show result
echo $result;

?>