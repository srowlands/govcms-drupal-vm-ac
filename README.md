## govCMS-devkit Acquia Cloud connector

These scripts are intended for use by [govCMS-devkit](https://github.com/srowlands/govcms-devkit).

## Setup

  1. `cd` into this directory and run `composer update` to install required dependencies.
  2. Make sure the environment variables `ACQUIA_CLOUD_USERNAME` and `ACQUIA_CLOUD_PASSWORD` are configured (for testing, please use your own credentials—the password is the 'Cloud API key' available though the Acquia Insight UI, e.g. at `https://accounts.acquia.com/account/[your-user-id]/security`).
  3. Make sure the environment variables `ACQUIA_CLOUD_SITE_ID` and `ACQUIA_CLOUD_ENVIRONMENT` are configured (`ACQUIA_CLOUD_ENVIRONMENT` refers to one of either dev, stage, prod).
  4. Run one of the scripts, e.g. `php database-dump.php` to generate a database backup for a specified site and environment.

