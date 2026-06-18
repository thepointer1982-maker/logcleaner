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

namespace OCA\LogCleaner\Controller;

use OCP\IL10N;
use OCP\IConfig;

class Helper
{
    private IConfig $config;
    private $appName;
    private $l;

    public function __construct(IConfig $config, IL10N $l, $appName){
        $this->config = $config;
        $this->l = $l;
        $this->appName = $appName;
    }

    public function getAppValue($key) {
        return $this->config->getAppValue($this->appName, $key);
    }

    public function setAppValue($key, $value) {
        return $this->config->setAppValue($this->appName, $key, $value);
    }

    public function wtlogtoarr(?string $wtlog): array
    {
        if ($wtlog === null || $wtlog === '' || !is_readable($wtlog)) {
            return [];
        }

        $lines = file($wtlog);
        if ($lines === false) {
            return [];
        }

        return $lines;
    }

    public function wtzeileweg(?int $wtzeile, ?array $wwt, ?string $wtlogfile): void
    {
        if ($wtzeile === null || $wwt === null || $wtlogfile === null || !is_writable($wtlogfile)) {
            return;
        }

        if ($wtzeile < 0 || $wtzeile >= count($wwt)) {
            return;
        }

        array_splice($wwt, $wtzeile, 1);
        file_put_contents($wtlogfile, $wwt, LOCK_EX);
    }

    private function createEmptyLogEntry(string $reason, int $all = 0, int $id = 0): \stdClass
    {
        $obja = new \stdClass();
        $obja->all = $all;
        $obja->zeit = '';
        $obja->ip = '';
        $obja->user = '';
        $obja->app = '';
        $obja->appraw = '';
        $obja->method = '';
        $obja->url = '';
        $obja->level = -1;
        $obja->error = 'alert alert-level3';
        $obja->grund = $reason;
        $obja->id = $id;
        return $obja;
    }

    private function setErrorClass(\stdClass $obja, $level): void
    {
        switch ((string)$level) {
          case '0':
            $obja->error = 'alert alert-level0';
            break;
          case '1':
            $obja->error = 'alert alert-level1';
            break;
          case '2':
            $obja->error = 'alert alert-level2';
            break;
          case '3':
            $obja->error = 'alert alert-level3';
            break;
          case '4':
            $obja->error = 'alert alert-level4';
            break;
          default:
            $obja->error = 'alert alert-level3';
        }
    }

    private function populateOutputData($wtlog, $wtall, $wtlogfilezeilen, $wt_characters, $wt_offset, bool $includeFilterFields): \stdClass
    {
        if ($wtall === 0) {
          return $this->createEmptyLogEntry($this->l->t('no log entries available'));
        }

        $trenn = '*';
        $json = json_decode((string)$wtlog);
        if (!is_object($json)) {
          return $this->createEmptyLogEntry($this->l->t('invalid log entry'), (int)$wtall, (int)$wtlogfilezeilen);
        }

        $obja = new \stdClass();
        $obja->all = $wtall;
        $time = isset($json->time) ? strtotime((string)$json->time) : false;
        if ($time !== false) {
          $wttimelog = $time + 3600 * (int)$wt_offset;
          $obja->zeit = $this->l->t('Time') . ' : ' . $this->l->l('date', $wttimelog) . ' - ' . $this->l->l('time', $wttimelog) . $trenn;
        } else {
          $obja->zeit = $this->l->t('Time') . ' : ' . $trenn;
        }
        $obja->ip = $this->l->t('IP') . ' :' . ($json->remoteAddr ?? '') . $trenn;
        $obja->user = $this->l->t('User') . ' :' . ($json->user ?? '') . $trenn;
        $obja->app = $this->l->t('App') . ' :' . ($json->app ?? '') . $trenn;
        if ($includeFilterFields) {
          $obja->appraw = (string)($json->app ?? '');
          $obja->level = $json->level ?? -1;
        }
        $obja->method = $this->l->t('Method') . ' :' . ($json->method ?? '') . $trenn;
        $obja->url = $this->l->t('URL') . ' :' . ($json->url ?? '') . $trenn;
        $obja->grund = $this->l->t('Reason') . ' :' . substr((string)($json->message ?? ''), 0, (int)$wt_characters);
        $this->setErrorClass($obja, $json->level ?? -1);
        $obja->id = $wtlogfilezeilen;
        return $obja;
    }

    public function myoutputdata($wtlog,$wtall,$wtlogfilezeilen,$wt_characters,$wt_offset) {
        return $this->populateOutputData($wtlog, $wtall, $wtlogfilezeilen, $wt_characters, $wt_offset, false);
    }

    public function myfilteredoutputdata($wtlog,$wtall,$wtlogfilezeilen,$wt_characters,$wt_offset, $level) {
        return $this->populateOutputData($wtlog, $wtall, $wtlogfilezeilen, $wt_characters, $wt_offset, true);
    }
}
