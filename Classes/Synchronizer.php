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

namespace Woeler\MauticCampaignMonitor;

use \Woeler\MauticCampaignMonitor\Service\MauticService;
use \Woeler\MauticCampaignMonitor\Service\CampaignMonitorService;


class Synchronizer
{

    private $mauticAuth;
    private $mauticService;
    private $campaignMonitorService;


    /**
     * Synchronizer constructor.
     * @param string $campaignMonitorApiKey
     * @param string $mauticUsername
     * @param string $mauticPassword
     * @param string $mauticUrl
     */
    function __construct(string $campaignMonitorApiKey, string $mauticUsername, string $mauticPassword, string $mauticUrl)
    {
        // Set globals
        $this->campaignMonitorService = new CampaignMonitorService($campaignMonitorApiKey);

        $this->mauticService = new MauticService($mauticUsername, $mauticPassword, $mauticUrl);

        $this->mauticAuth = $this->mauticService->mauticAuthorization();

    }


    /**
     * @return string
     */
    public function execute(): string
    {
        // Compare contacts in Mautic and CM to check for duplicated
        $unknownContacts = $this->compareMauticToCampaignMonitor($this->getMauticContacts(), $this->getCampaignMonitorClients());

        // For each contact unknown to mautic
        foreach ($unknownContacts as $email => $name) {

            // Separate first and last name
            $names = explode(' ', $name, 2);
            $firstName = $names[0];
            $lastName = $names[1];

            // Array to post to Mautic
            $toSend = array('firstname' => $firstName, 'lastname' => $lastName, 'email' => $email);

            // Create Mautic API
            $contactApi = $this->mauticService->createMauticApi('contacts', $this->mauticAuth);

            // Send
            $contactApi->create($toSend);

        }

        // Return message
        return 'SUCCESS: ' . count($unknownContacts) . ' contacts successfully exported to Mautic.';

    }

    /**
     * @param array $mauticContacts
     * @param array $campaignMonitorContacts
     * @return array
     */
    private function compareMauticToCampaignMonitor(array $mauticContacts, array $campaignMonitorContacts): array
    {

        // Instantiate array
        $notInMauticContacts = array();

        // For each contact in CM
        foreach ($campaignMonitorContacts as $email => $name) {

            // If email address not known in Mautic
            if (!array_key_exists($email, $mauticContacts)) {

                // Add contact to array
                $notInMauticContacts[$email] = $name;

            }

        }

        // Return contacts not in Mautic
        return $notInMauticContacts;

    }

    /**
     * @return array
     */
    private function getCampaignMonitorClients(): array
    {

        // Create new general CM API
        $api = $this->campaignMonitorService->createCampaignMonitorApi('general');

        // Get clients
        $clients = $api->get_clients();

        $clientArray = array();

        // For each client
        foreach ($clients->response as $client) {

            // Create client API
            $clientApi = $this->campaignMonitorService->createCampaignMonitorApi('clients', $client->ClientID);

            // Get subscriber lists
            $lists = $clientApi->get_lists();

            // For each list
            foreach ($lists->response as $list) {

                // Create list API
                $listApi = $this->campaignMonitorService->createCampaignMonitorApi('lists', $list->ListID);

                // Get active subscribers in the list
                $subscribers = $listApi->get_active_subscribers();

                // For each subscriber
                foreach ($subscribers->response->Results as $subscriber) {

                    // Check for duplicates
                    if (!key_exists($subscriber->EmailAddress, $clientArray)) {
                        // Add subscriber to array
                        $clientArray[$subscriber->EmailAddress] = $subscriber->Name;
                    }

                }
            }
        }

        // Return array
        return $clientArray;

    }

    /**
     * @return array
     */
    private function getMauticContacts(): array
    {

        // Create Mautic API
        $contactApi = $this->mauticService->createMauticApi('contacts', $this->mauticAuth);

        // Get list of Mautic contacts
        $list = $contactApi->getList('', 0, 99999999);

        $contactsArray = array();

        // For each contact
        foreach ($list['contacts'] as $contact) {

            // Set correct name
            $name = $contact['fields']['core']['firstname']['value'] . ' ' . $contact['fields']['core']['lastname']['value'];

            // Add to array
            $contactsArray[$contact['fields']['core']['email']['value']] = $name;

        }

        // Return array
        return $contactsArray;

    }

}

?>