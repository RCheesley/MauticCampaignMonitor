# CampaignMonitor to Mautic Contact sync

Sync contacts from campaignmonitor to Mautic.

Version: 0.1.0

Dependencies: 
* PHP 7.1
* Mautic API library - 2.6 or higher
* CampaignMonitor CreateSend PHP - 5.1 or higher

(Last two included in composer.json)

State: Beta

This piece of software will allow
you to export your subscribers in CampaignMonitor to Mautic

You can use the ExampleUsage.php script or implement it yourself

The parameters you will need to configure are:
* CampaignMonitor API key
* Mautic username
* Mautic password
* URL of the Mautic installation

NOTE: I highly recommend to only use this application
while use HTTPS/SSL

The most useful way to set this app up is as a cronjob