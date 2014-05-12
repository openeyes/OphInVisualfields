<?php
/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<?php if ($elements): ?>
	<?php foreach ($elements as $element): ?>
		<div id="OphInVisualfields_Episode_VisualFieldsHistory_element_<?= $element->id ?>" class="OphInVisualfields_Episode_VisualFieldsHistory_element element-fields element-eyes hidden">
			<?php
				$this->render(get_class($this) . '_side', array('element' => $element, 'side' => 'right'));
				$this->render(get_class($this) . '_side', array('element' => $element, 'side' => 'left'));
			?>
		</div>
	<?php endforeach ?>
	<div id="OphInVisualfields_Episode_VisualFieldsHistory_slider"></div>
	<script>
		$(document).ready(function () {
			function showElement(elementId) {
				$('#OphInVisualfields_Episode_VisualFieldsHistory_element_' + elementId).show();
			}

			var elementIds = window.OphInVisualfields_Episode_VisualFieldsHistory_element_ids;

			$('#OphInVisualfields_Episode_VisualFieldsHistory_slider').slider({
				'min': 0,
				'max': elementIds.length - 1,
				'value': elementIds.length - 1,
				'slide': function (e, ui) {
					$('.OphInVisualfields_Episode_VisualFieldsHistory_element').hide();
					showElement(elementIds[ui.value]);
				},
			});

			showElement(elementIds[elementIds.length - 1]);
		});
	</script>
<?php else: ?>
	<div class="data-value">No visual field images recorded for this patient.</div>
<?php endif ?>
