<?php
/**
 * @file
 * Deployment script using Acquia Cloud PHP SDK.
 *
 * The following environment variables must be set:
 *   - ACQUIA_CLOUD_USERNAME / ACQUIA_CLOUD_PASSWORD
 *   - ACQUIA_CLOUD_ENVIRONMENT (e.g. 'dev', 'test', 'prod')
 *   - ACQUIA_CLOUD_SITENAME (e.g. 'sitename')
 *
 */

require_once 'vendor/autoload.php';

// Buffer output.
ob_start();

use Acquia\Cloud\Api\CloudApiClient;

// Build Cloud API client connection.
$cloudapi = CloudApiClient::factory(array(
  'username' => getenv('ACQUIA_CLOUD_USERNAME'),
  'password' => getenv('ACQUIA_CLOUD_PASSWORD'),
));

// Set up other required variables.
$environment = getenv('ACQUIA_CLOUD_ENVIRONMENT');
$site = getenv('ACQUIA_CLOUD_SITENAME');

// Create a database backup (wait for completion).
update_console('Backing up database in ' . $environment . ' environment...');

$backup = $cloudapi->createDatabaseBackup('devcloud:' . $site, $environment, $site);
if (wait_for_task_to_complete($cloudapi, 'devcloud:' . $site, $backup->id())) {
  update_console('...complete!');
  $result = json_decode($cloudapi->task('devcloud:' . $site, $backup->id())->result());
  $bid = $result->backupid;
}

$download = $cloudapi->downloadDatabaseBackup('devcloud:' . $site, $environment, $site, $bid, 'test.sql.gz');

/**
 * Pause until a given task is completed.
 *
 * @param int $id
 *   The task ID.
 *
 * @todo - This currently will loop infinitely if you pass an invalid task id.
 *   Consider fixing that ;-)
 */
function wait_for_task_to_complete($cloudapi, $site, $id = 0) {
  $task_complete = FALSE;
  while ($task_complete !== TRUE) {
    $task_status = $cloudapi->task($site, $id);
    if ($task_status->state() == 'done') {
      $task_complete = TRUE;
    }
    else {
      sleep(5);
    }
  }
  return $task_complete;
}
/**
 * Post a string to the console mid-script.
 *
 * @param string $text
 */
function update_console($text) {
  echo $text . "\n";
  ob_flush();
}
// Flush all output.
ob_end_flush();
