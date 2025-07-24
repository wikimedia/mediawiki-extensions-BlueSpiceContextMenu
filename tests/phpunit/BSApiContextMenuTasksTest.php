<?php

use BlueSpice\Tests\BSApiTasksTestBase;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

/**
 * @group medium
 * @group API
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceExtensions
 * @group BlueSpiceContextMenu
 * @covers \BlueSpice\ContextMenu\Api\ContextMenuTasks
 */
class BSApiContextMenuTasksTest extends BSApiTasksTestBase {

	protected function setUp(): void {
		parent::setUp();

		// Disable email functionality to prevent 'Send mail' from appearing
		$this->overrideConfigValue( MainConfigNames::EnableEmail, false );
		$this->overrideConfigValue( MainConfigNames::EnableUserEmail, false );

		$file = $this->getServiceContainer()->getRepoGroup()->getLocalRepo()->newFile(
			Title::makeTitle( NS_FILE, 'File.txt' )
		);

		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/data/file.txt';

		$archive = $file->publish( $filepath );
		$mwProps = new MWFileProps( $this->getServiceContainer()->getMimeAnalyzer() );
		$props = $mwProps->getPropsFromPath( $filepath, true );
		$file->recordUpload3( $archive->value, 'Test', 'Test', $user, $props );

		$this->insertPage( 'ContextMenuPage' );
	}

	protected function getModuleName() {
		return 'bs-contextmenu-tasks';
	}

	/**
	 * @dataProvider provideGetMenuItemData
	 */
	public function testGetMenuItems( $title, $expectedResultFlag, $expectedNoOfEntries ) {
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

		$this->assertCount( $expectedNoOfEntries, $items,
			'The number of returned items did not match expectations' );
	}

	public function provideGetMenuItemData() {
		return [
			'no title set ' => [ '', false, 0 ],
			'normal wiki page' => [ 'ContextMenuPage', true, 9 ],
			'normal non existing wiki page' => [ 'Page does not exist', true, 4 ],
			'non existing user page' => [ 'User:NotExist', true, 5 ],
			'file page' => [ 'File:File.txt', true, 12 ]
		];
	}
}
