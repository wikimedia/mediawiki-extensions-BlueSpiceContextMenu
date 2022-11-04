<?php

use BlueSpice\Tests\BSApiTasksTestBase;
use MediaWiki\MediaWikiServices;

/**
 * @group medium
 * @group API
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceExtensions
 * @group BlueSpiceContextMenu
 */
class BSApiContextMenuTasksTest extends BSApiTasksTestBase {

	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgEmailAuthentication' => false
		] );

		$file = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()->newFile(
			Title::makeTitle( NS_FILE, 'File.txt' )
		);

		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/data/file.txt';

		$archive = $file->publish( $filepath );
		$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
		$props = $mwProps->getPropsFromPath( $filepath, true );
		$file->recordUpload3( $archive->value, 'Test', 'Test', $user, $props );
	}

	protected function getModuleName() {
		return 'bs-contextmenu-tasks';
	}

	/**
	 * @covers \BSApiContextMenuTasks::task_getMenuItems
	 * @dataProvider provideGetMenuItemData
	 */
	public function testGetMenuItems( $title, $expectedResultFlag, $expectedNoOfEintries ) {
		$response = $this->executeTask( 'getMenuItems', [
			'title' => $title
		] );

		$this->assertEquals( $expectedResultFlag, $response->success,
			'The "success" flag did not match expectations' );

		$items = [];
		if ( $response->success === true ) {
			$response = (array)$response;
			$items = $response['payload']['items'];
		}

		$this->assertCount( $expectedNoOfEintries, $items,
			'The number of returned items did not match expectations' );
	}

	public function provideGetMenuItemData() {
		return [
			'no title set ' => [ '', false, 0 ],
			'normal wiki page' => [ 'UTPage', true, 9 ],
			'normal non existing wiki page' => [ 'Page does not exist', true, 4 ],
			'non existing user page' => [ 'User:UTSysop', true, 5 ],
			'file page' => [ 'File:File.txt', true, 12 ],
		];
	}
}
