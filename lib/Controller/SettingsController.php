<?php

/**
 * Nextcloud - U2F 2FA
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @copyright Christoph Wurst 2016
 */

namespace OCA\TwoFactor_U2F\Controller;

require_once(__DIR__ . '/../../vendor/yubico/u2flib-server/src/u2flib_server/U2F.php');

use OCA\TwoFactor_U2F\Service\U2FManager;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IUserSession;

class SettingsController extends Controller {

	/** @var U2FManager */
	private $manager;

	/** @var IUserSession */
	private $userSession;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param U2FManager $manager
	 * @param IUserSession $userSession
	 */
	public function __construct($appName, IRequest $request, U2FManager $manager, IUserSession $userSession) {
		parent::__construct($appName, $request);
		$this->manager = $manager;
		$this->userSession = $userSession;
	}

	/**
	 * @NoAdminRequired
	 */
	public function state() {
		return [
			'enabled' => $this->manager->isEnabled($this->userSession->getUser())
		];
	}

	/**
	 * @NoAdminRequired
	 */
	public function disable() {
		$this->manager->disableU2F($this->userSession->getUser());
	}

	/**
	 * @NoAdminRequired
	 * @UseSession
	 */
	public function startRegister() {
		return $this->manager->startRegistration($this->userSession->getUser());
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string $registrationData
	 * @param string $clientData
	 */
	public function finishRegister($registrationData, $clientData) {
		$this->manager->finishRegistration($this->userSession->getUser(), $registrationData, $clientData);
	}

}
