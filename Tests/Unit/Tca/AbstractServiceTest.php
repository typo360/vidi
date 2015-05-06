<?php
namespace Fab\Vidi\Tests\Unit\Tca;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test case for class \Fab\Vidi\Tca\GridService.
 */
abstract class AbstractServiceTest extends UnitTestCase {

	/**
	 * @var \Fab\Vidi\Tca\GridService
	 */
	private $fixture;

	public function setUp() {
		parent::setUp();

		$GLOBALS['TCA']['tx_foo'] = [
			'ctrl' => [
				'label' => 'username',
				'default_sortby' => 'ORDER BY username',
				'tstamp' => 'tstamp',
				'crdate' => 'crdate',
				'cruser_id' => 'cruser_id',
				'title' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:tx_foo',
				'delete' => 'deleted',
				'enablecolumns' => [
					'disabled' => 'disable',
					'starttime' => 'starttime',
					'endtime' => 'endtime'
				],
				'typeicon_classes' => [
					'default' => 'status-user-frontend'
				],
				'searchFields' => 'username,name,first_name,last_name'
			],
			'columns' => [
				'username' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:username',
					'config' => [
						'type' => 'input',
						'size' => '20',
						'max' => '255',
						'eval' => 'nospace,lower,uniqueInPid,required'
					]
				],
				'password' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:password',
					'config' => [
						'type' => 'input',
						'size' => '10',
						'max' => '40',
						'eval' => 'nospace,required,password'
					]
				],
				'usergroup' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:usergroup',
					'config' => [
						'type' => 'select',
						'foreign_table' => 'fe_groups',
						'foreign_table_where' => 'ORDER BY fe_groups.title',
						'size' => '6',
						'minitems' => '1',
						'maxitems' => '50'
					]
				],
				'name' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:name',
					'config' => [
						'type' => 'input',
						'size' => '40',
						'eval' => 'trim',
						'max' => '80'
					]
				],
				'first_name' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:first_name',
					'config' => [
						'type' => 'input',
						'size' => '25',
						'eval' => 'trim',
						'max' => '50'
					]
				],
				'last_name' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:last_name',
					'config' => [
						'type' => 'input',
						'size' => '25',
						'eval' => 'trim',
						'max' => '50'
					]
				],
				'middle_name' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:middle_name',
					'config' => [
						'type' => 'input',
						'size' => '25',
						'eval' => 'trim',
						'max' => '50'
					]
				],
				'alternative_name' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:alternative_name',
					'config' => [
						'type' => 'input',
						'size' => '25',
						'eval' => 'trim',
						'max' => '50'
					]
				],
				'birthday' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:birthday',
					'config' => [
						'type' => 'input',
						'size' => '25',
						'eval' => 'date',
						'max' => '50'
					]
				],
				'starttime' => [
					'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:starttime',
					'config' => [
						'type' => 'input',
						'size' => '13',
						'max' => '20',
						'eval' => 'datetime',
						'default' => '0'
					]
				],
			],
			'grid' => [
				'excluded_fields' => 'middle_name, alternative_name',
				'columns' => [
					'username' => [
						'visible' => TRUE,
						'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:username',
						'editable' => TRUE,
					],
					'name' => [
						'visible' => TRUE,
						'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:name',
						'editable' => TRUE,
					],
					'usergroup' => [
						'visible' => TRUE,
						'renderers' => [
							'Fab\Vidi\Grid\RelationEditRenderer',
							'Fab\Vidi\Grid\RelationRenderer',
						],
						'editable' => TRUE,
						'sortable' => FALSE,
						'label' => 'LLL:EXT:foo/Resources/Private/Language/tx_foo.xlf:usergroup',
					],
				]
			]
		];

		$GLOBALS['TYPO3_DB'] = $this->getMock('TYPO3\CMS\Core\Database\DatabaseConnection', []);

		/*
		#################
		# opposite many
		# one-to-many
		$TCA['tx_foo_domain_model_book'] = array(
			'columns' => array(
				'access_codes' => array(
					'config' => array(
						'type' => 'inline',
						'foreign_table' => 'tx_foo_domain_model_accesscode',
						'foreign_field' => 'book',
						'maxitems' => 9999,
					),
				),
			),
		);

		# opposite one
		# many-to-one
		$TCA['tx_foo_domain_model_accesscode'] = array(
			'columns' => array(
				'book' => array(
					'config' => array(
						'type' => 'select',
						'foreign_table' => 'tx_foo_domain_model_book',
						'foreign_field' => 'access_codes',
						'minitems' => 1,
						'maxitems' => 1,
					),
				),
			),
		);

		#################
		# Many to many
		$TCA['tx_foo_domain_model_book'] = array(
			'columns' => array(
				'tx_myext_locations' => array(
					'config' => array(
						'type' => 'select',
						'foreign_table' => 'tx_foo_domain_categories',
						'MM_opposite_field' => 'usage_mm',
						'MM' => 'tx_foo_domain_categories_mm',
						'MM_match_fields' => array(
							'tablenames' => 'pages'
						),
						'size' => 5,
						'maxitems' => 100
					)
				)
			),
		);

		$TCA['tx_foo_domain_categories'] = array(
			'columns' => array(
				'usage_mm' => array(
					'config' => array(
						'type' => 'group',
						'internal_type' => 'db',
						'allowed' => 'pages,tt_news',
						'prepend_tname' => 1,
						'size' => 5,
						'maxitems' => 100,
						'MM' => 'tx_foo_domain_categories_mm'
					)
				)
			),
		);

		#################
		# Legacy MM relation
		$TCA['tx_foo_domain_model_book'] = array(
			'columns' => array(
				'fe_groups' => array(
					'config' => array(
						'type' => 'inline',
						'foreign_table' => 'tx_foo_domain_model_accesscode',
						'foreign_field' => 'book',
						'maxitems' => 9999,
					),
				),
			),
		);

		*/

	}

	public function tearDown() {
		unset($this->fixture, $GLOBALS['TCA'], $GLOBALS['TYPO3_DB']);
	}

}