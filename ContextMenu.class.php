<?php


/**
 * BlueSpice MediaWiki
 * Extension: ContextMenu
 * Description: Provides context menus for various MediaWiki links
 * Authors: Tobias Weichart, Robert Vogel
 *
 * Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * For further information visit http://www.bluespice.com
 * @author     Robert Vogel <vogel@hallowelt.com>
 * @package    BlueSpice_Extensions
 * @subpackage ContextMenu
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

class ContextMenu extends BsExtensionMW {

	/**
	 * Initialization of ContextMenu extension
	 */
	protected function initExt() {
		$this->setHook('BeforePageDisplay');
	}

	/**
	 * Adds resources to ResourceLoader
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return boolean Always true to keep hook running
	 */
	public function onBeforePageDisplay(&$out, &$skin) {
		$out->addModules('ext.bluespice.contextmenu');

		//We check if the current user can send Mails trough the wiki
		//TODO: Maybe move to BSF?
		$mEMailPermissioErrors = SpecialEmailUser::getPermissionsError(
			$this->getUser(), $this->getUser()->getEditToken()
		);

		$bUserCanSendMail = false;
		if ($mEMailPermissioErrors === null) {
			$bUserCanSendMail = true;
		}

		$out->addJsConfigVars( 'bsUserCanSendMail', $bUserCanSendMail );

		return true;
	}

}
