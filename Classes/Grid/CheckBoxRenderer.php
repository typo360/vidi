<?php
namespace Fab\Vidi\Grid;

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

use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for rendering the "Check Box" in the Grid.
 */
class CheckBoxRenderer extends GridRendererAbstract {

	/**
	 * Render the "Check Box" in the Grid.
	 *
	 * @return string
	 */
	public function render() {

		return sprintf('<input type="checkbox" class="checkbox-row" data-index="%s" data-uid="%s"/>',
			$this->getRowIndex(),
			$this->getObject()->getUid()
		);
	}
}
