/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */

import { SelectControl, TextControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {

	console.log(attributes.tourCount);

	return (
		<div {...useBlockProps()}>
			<TextControl
				label={__('Button Text', 'fsrgblocks')}
				value={attributes.buttonText}
				onChange={(value) => setAttributes({ buttonText: value })}
			/>
			<TextControl
				label={__('Anzahl Rundgänge im Karousel', 'fsrgblocks')}
				value={attributes.tourCount}
				type="number"
				onChange={(value) => setAttributes({ tourCount: value })}
			/>
			<SelectControl
				label={__('Hoehen Einheit', 'fsrgblocks')}
				value={attributes.heightUnit}
				options={[
					{ label: 'px', value: 'px' },
					{ label: 'vh', value: 'vh' },
				]}
				onChange={(value) => setAttributes({ heightUnit: value })}
			/>
			<SelectControl
				label={__('Breite Einheit', 'fsrgblocks')}
				value={attributes.widthUnit}
				options={[
					{ label: '%', value: '%' },
					{ label: 'px', value: 'px' },
					{ label: 'vw', value: 'vw' },
				]}
				onChange={(value) => setAttributes({ widthUnit: value })}
			/>
			<TextControl
				label={__('Hoehe des Karousels auf Desktop', 'fsrgblocks')}
				value={attributes.heightValueDesktop}
				type="number"
				onChange={(value) => setAttributes({ heightValueDesktop: value })}
			/>
			<TextControl
				label={__('Breite eines Sliders im Karousel auf Desktop', 'fsrgblocks')}
				value={attributes.widthValueDesktop}
				type="number"
				onChange={(value) => setAttributes({ widthValueDesktop: value })}
			/>
			<TextControl
				label={__('Hoehe des Karousels auf Mobile', 'fsrgblocks')}
				value={attributes.heightValueMobile}
				type="number"
				onChange={(value) => setAttributes({ heightValueMobile: value })}
			/>
			<TextControl
				label={__('Breite eines Sliders im Karousel auf Mobile', 'fsrgblocks')}
				value={attributes.widthValueMobile}
				type="number"
				onChange={(value) => setAttributes({ widthValueMobile: value })}
			/>
			<TextControl
				label={__('Zeit zwischen Slide Wechsel in Karousel, in Millisekunden. Setze auf 0 um autoplay zu deaktivieren', 'fsrgblocks')}
				value={attributes.autoPlayDelayInMs}
				type="number"
				onChange={(value) => setAttributes({ autoPlayDelayInMs: value })}
			/>
			<TextControl
				label={__('Farbe fuer aktiven Paginator und Textrand', 'fsrgblocks')}
				value={attributes.colorActive}
				onChange={(value) => setAttributes({ colorActive: value })}
			/>
			<TextControl
				label={__('Farbe fuer den inaktiven Paginator (punkt)', 'fsrgblocks')}
				value={attributes.colorInactive}
				onChange={(value) => setAttributes({ colorInactive: value })}
			/>
			<TextControl
				label={__('Farbe fuer den Text in den Slides', 'fsrgblocks')}
				value={attributes.colorText}
				onChange={(value) => setAttributes({ colorText: value })}
			/>
			<TextControl
				label={__('Farbe fuer den Text-Hintergrund in den Slides', 'fsrgblocks')}
				value={attributes.colorBackground}
				onChange={(value) => setAttributes({ colorBackground: value })}
			/>
			<i>Zeige die naechsten {attributes.tourCount} Rundgaegnge im Karousel</i>
		</div>
	);
}
