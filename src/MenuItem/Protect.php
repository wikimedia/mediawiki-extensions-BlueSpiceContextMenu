<?php

namespace BlueSpice\ContextMenu\MenuItem;

use MediaWiki\Message\Message;

class Protect extends Base {

	/**
	 *
	 * @return string
	 */
	public function getIconClass() {
		return 'lock';
	}

	/**
	 *
	 * @return Message
	 */
	public function getLabelMessage() {
		return wfMessage( 'bs-contextmenu-page-protect' );
	}

	/**
	 *
	 * @return string String of the URL.
	 */
	public function getUrl() {
		return $this->title->getLocalUrl( [ 'action' => 'protect' ] );
	}

	/**
	 *
	 * @return string
	 */
	public function getId() {
		return 'bs-cm-item-protect';
	}

	/**
	 *
	 * @param \Context $context
	 * @return bool
	 */
	public function shouldList( $context ) {
		return $this->title->isKnown() &&
			\MediaWiki\MediaWikiServices::getInstance()
				->getPermissionManager()
				->userCan( 'protect', $context->getUser(), $this->title );
	}

}
