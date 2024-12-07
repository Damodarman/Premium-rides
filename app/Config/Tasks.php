<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Tasks.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Tasks\Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Tasks\Scheduler;

class Tasks extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Should performance metrics be logged
     * --------------------------------------------------------------------------
     *
     * If true, will log the time it takes for each task to run.
     * Requires the settings table to have been created previously.
     */
    public bool $logPerformance = false;

    /**
     * --------------------------------------------------------------------------
     * Maximum performance logs
     * --------------------------------------------------------------------------
     *
     * The maximum number of logs that should be saved per Task.
     * Lower numbers reduced the amount of database required to
     * store the logs.
     */
    public int $maxLogsPerTask = 10;

    /**
     * Register any tasks within this method for the application.
     * Called by the TaskRunner.
     */
	public function init(Scheduler $schedule)
	{
		// Hourly Backup Task
		$schedule->command('backup:create hourly')
			->hourly(0) // Runs every hour
			->named('hourlyBackup');

		// Daily Backup Task
		$schedule->command('backup:create daily')
			->daily() // Runs every day at 11:59 PM
			->named('dailyBackup');

		// Weekly Backup Task
		$schedule->command('backup:create weekly')
			->mondays() // Runs every Sunday at 11:59 PM
			->named('weeklyBackup');

		// Monthly Backup Task
		$schedule->command('backup:create monthly')
			->monthly() // Runs on the first of every month at 11:59 PM
			->named('monthlyBackup');
	}
	
}
