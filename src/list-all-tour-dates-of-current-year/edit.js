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

import { SelectControl } from '@wordpress/components';

export default function Edit({attributes, setAttributes}) {


	// allow selection of the next 20 years for the tour date

	const blockYear = attributes.seasonYear ? attributes.seasonYear : new Date().getFullYear();
	const years = Array.from({length: 20}, (v, k) => new Date().getFullYear() + k);

	return (
		<div { ...useBlockProps() }>
			<SelectControl
				label={ __( 'Waehle das Saison-Jahr aus', 'fsrgblocks' ) }
				value={ blockYear }
				options={ years.map( year => {
					return { label: year, value: year }
				} ) }
				onChange={ ( value ) => setAttributes({ seasonYear: value })}
			/>
			<i>Zeige alle Rundgaegnge fuer das Jahr {blockYear}</i>
		</div>
	);
}
