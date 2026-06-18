<?php
/**
 *
 * LogCleaner APP (Nextcloud)
 *
 * @author Wolfgang Tödt <wtoedt@gmail.com>
 *
 * @copyright Copyright (c) 2025 Wolfgang Tödt
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace OCA\LogCleaner\Dashboard;

use OCP\AppFramework\Services\IInitialState;
use OCP\Dashboard\IAPIWidgetV2;
use OCP\Dashboard\IConditionalWidget;
use OCP\Dashboard\Model\WidgetItems;
use OCP\Dashboard\Model\WidgetItem;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\Util;

class LogCleanerWidget implements IAPIWidgetV2, IConditionalWidget {
	private IL10N $l10n;
	private IConfig $config;
	private IInitialState $initialStateService;
	private ?string $userId;
	private bool $wtisadmin = false;

	public function __construct(
		IL10N $l10n,
		private readonly IURLGenerator $urlGenerator,
		IConfig $config,
		IUserSession $userSession,
		IGroupManager $groupManager,
		IInitialState $initialStateService,
		?string $userId
	) {
		$this->l10n = $l10n;
		$this->config = $config;
		$this->initialStateService = $initialStateService;
		$this->userId = $userId;
		$user = $userSession->getUser();
		if ($user !== null) {
			$this->wtisadmin = $groupManager->isAdmin($user->getUID());
		}
	}

	public function isEnabled(): bool {
		return $this->wtisadmin;
	}

	public function getId(): string {
		return 'logcleanerdashboard-logcleaner-widget';
	}

	public function getTitle(): string {
		return $this->l10n->t('LogCleaner 1');
	}

	public function getOrder(): int {
		return 10;
	}

	public function getIconClass(): string {
		return 'icon-logcleaner';
	}

	public function getIconUrl(): string {
		return $this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('logcleaner', 'logcleaner-dark.svg'));
	}

	public function getUrl(): ?string {
		return null;
	}

	public function load(): void {
		Util::addStyle('logcleaner', 'logcleanerwidget');
	}

	public function getItems(string $userId, int $limit = 7): array {
		$wtlogfile = $this->getLogFile();
		$logFileLabel = $wtlogfile !== '' ? $wtlogfile : $this->l10n->t('log file cannot be located');
		$logcleaneritems = [];
		$logcleaneritems[] = new WidgetItem(
			$logFileLabel,
			$this->show_filesize($wtlogfile, 2),
			$this->urlGenerator->getAbsoluteURL($this->urlGenerator->linkToRoute('logcleaner.page.index')),
			$this->urlGenerator->imagePath('logcleaner', 'icon-file.png'),
			''
		);
		$logcleaneritems[] = new WidgetItem(
			$this->l10n->n('%n log entry', '%n log entries', $this->getAll()),
			'',
			$this->urlGenerator->getAbsoluteURL($this->urlGenerator->linkToRoute('logcleaner.page.index')),
			$this->urlGenerator->imagePath('logcleaner', 'logcleaner.png'),
			''
		);
		$logcleaneritems[] = new WidgetItem(
			$this->l10n->n('%n duplicate', '%n duplicates', $this->countDub()),
			'',
			$this->urlGenerator->getAbsoluteURL($this->urlGenerator->linkToRoute('logcleaner.page.index')),
			$this->urlGenerator->imagePath('logcleaner', 'logcleaner.png'),
			''
		);
		return $logcleaneritems;
	}

	public function getItemsV2(string $userId, ?string $since = null, int $limit = 7): WidgetItems {
		$items = $this->getItems($userId, $limit);
		return new WidgetItems(
			$items,
			'',
		);
	}

	private function getLogFile(): string {
		$wtlogfile = (string)$this->config->getSystemValue('logfile');
		if ($wtlogfile !== '' && is_file($wtlogfile)) {
			return $wtlogfile;
		}

		$fallback = (string)$this->config->getSystemValue('datadirectory') . '/nextcloud.log';
		if (is_file($fallback)) {
			return $fallback;
		}

		return '';
	}

	public function show_filesize($filename, $decimalplaces = 0) {
		if ($filename === '' || !is_file($filename)) {
			return '0 B';
		}
		$size = filesize($filename);
		if ($size === false) {
			return '0 B';
		}
		$sizes = array('B', 'kB', 'MB', 'GB', 'TB');
		for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) {
			$size /= 1024;
		}
		return round($size, $decimalplaces).' '.$sizes[$i];
	}

	public function getAll() {
		$wwt = $this->wtlogtoarr($this->getLogFile());
		return count($wwt);
	}

	public function wtlogtoarr(?string $wtlog): array
	{
		if ($wtlog === null || $wtlog === '' || !is_readable($wtlog)) {
			return [];
		}
		$lines = file($wtlog);
		return $lines === false ? [] : $lines;
	}

	public function countDub() {
		$messages = [];
		$duplicates = 0;
		foreach ($this->wtlogtoarr($this->getLogFile()) as $value) {
			$json = json_decode($value, true);
			if (!is_array($json) || !array_key_exists('message', $json)) {
				continue;
			}
			$message = (string)$json['message'];
			if (isset($messages[$message])) {
				$duplicates++;
				continue;
			}
			$messages[$message] = true;
		}
		return $duplicates;
	}
}
