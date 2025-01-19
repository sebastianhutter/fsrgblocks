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

import { SelectControl, TextControl, ColorPalette, Flex, FlexItem } from '@wordpress/components';


const defaultColors = [
	{ name: 'Grün', color: '#d1d81e' },
	{ name: 'Braun ohne Transparenz', color: '#3c2c29' },
	{ name: 'Braun mit Transparenz', color: '#3c2c294d' },
	{ name: 'Weiss', color: '#f9f9f9' },
	{ name: 'Grau', color: '#e0e0e0' },
];

export default function Edit({ attributes, setAttributes }) {

	console.log(attributes.tourCount);

	return (
		<div {...useBlockProps()}>
			<h4>{__('Allgemeine Konfiguration', 'fsrgblocks')}</h4>
			<TextControl
				label={__('Anzahl Rundgänge im Karousel', 'fsrgblocks')}
				value={attributes.tourCount}
				type="number"
				onChange={(value) => setAttributes({ tourCount: value })}
			/>
			<TextControl
				label={__('Zeit zwischen Slide Wechsel in Karousel, in Millisekunden. Setze auf 0 um autoplay zu deaktivieren', 'fsrgblocks')}
				value={attributes.autoPlayDelayInMs}
				type="number"
				onChange={(value) => setAttributes({ autoPlayDelayInMs: value })}
			/>
			<TextControl
				label={__('Button Text', 'fsrgblocks')}
				value={attributes.buttonText}
				onChange={(value) => setAttributes({ buttonText: value })}
			/>
			<h4>{__('Hoehe und Breite des Karousels', 'fsrgblocks')}</h4>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<SelectControl
						label={__('Hoehen Einheit', 'fsrgblocks')}
						value={attributes.heightUnit}
						options={[
							{ label: 'px', value: 'px' },
							{ label: 'vh', value: 'vh' },
						]}
						onChange={(value) => setAttributes({ heightUnit: value })}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
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
				</FlexItem>
			</Flex>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Hoehe des Karousels auf Desktop', 'fsrgblocks')}
						value={attributes.heightValueDesktop}
						type="number"
						onChange={(value) => setAttributes({ heightValueDesktop: value })}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Breite eines Sliders im Karousel auf Desktop', 'fsrgblocks')}
						value={attributes.widthValueDesktop}
						type="number"
						onChange={(value) => setAttributes({ widthValueDesktop: value })}
					/>
				</FlexItem>
			</Flex>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Hoehe des Karousels auf Mobile', 'fsrgblocks')}
						value={attributes.heightValueMobile}
						type="number"
						onChange={(value) => setAttributes({ heightValueMobile: value })}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Breite eines Sliders im Karousel auf Mobile', 'fsrgblocks')}
						value={attributes.widthValueMobile}
						type="number"
						onChange={(value) => setAttributes({ widthValueMobile: value })}
					/>
				</FlexItem>
			</Flex>
			<h4>{__('Schrift', 'fsrgblocks')}</h4>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Schriftgroesse Titel auf Desktop', 'fsrgblocks')}
						value={attributes.fontSizeTitleDesktop}
						type="number"
						onChange={(value) => setAttributes({ fontSizeTitleDesktop: value })}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Schriftgroesse Titel auf Mobile', 'fsrgblocks')}
						value={attributes.fontSizeTitleMobile}
						type="number"
						onChange={(value) => setAttributes({ fontSizeTitleMobile: value })}
					/>
				</FlexItem>
			</Flex>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Schriftgroesse Datum auf Desktop', 'fsrgblocks')}
						value={attributes.fontSizeDateDesktop}
						type="number"
						onChange={(value) => setAttributes({ fontSizeDateDesktop: value })}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<TextControl
						label={__('Schriftgroesse Datum auf Mobile', 'fsrgblocks')}
						value={attributes.fontSizeDateMobile}
						type="number"
						onChange={(value) => setAttributes({ fontSizeDateMobile: value })}
					/>
				</FlexItem>
			</Flex>
			<h4>{__('Farben', 'fsrgblocks')}</h4>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint">{__('Farbe fuer aktiven Paginator (punkt am unteren rand des karousels)', 'fsrgblocks')}</span>
					<ColorPalette
						value={attributes.colorActive}
						onChange={(value) => setAttributes({ colorActive: value })}
						enableAlpha={true}
						colors={defaultColors}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint">{__('Farbe fuer inaktiven Paginator (punkt am unteren rand des karousels)', 'fsrgblocks')}</span>
					<ColorPalette
						value={attributes.colorInactive}
						onChange={(value) => setAttributes({ colorInactive: value })}
						enableAlpha={true}
						colors={defaultColors}
					/>
				</FlexItem>
			</Flex>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint">{__('Farbe fuer den Text in den Slides', 'fsrgblocks')}</span>
					<ColorPalette
						value={attributes.colorText}
						onChange={(value) => setAttributes({ colorText: value })}
						enableAlpha={true}
						colors={defaultColors}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint">{__('Farbe fuer den Text-Schatten in den Slides', 'fsrgblocks')}</span>
					<ColorPalette
						value={attributes.colorTextShadow}
						onChange={(value) => setAttributes({ colorTextShadow: value })}
						enableAlpha={true}
						colors={defaultColors}
					/>
				</FlexItem>
			</Flex>
			<Flex>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint">{__('Farbe fuer den Text-Hintergrund in den Slides', 'fsrgblocks')}</span>
					<ColorPalette
						value={attributes.colorBackground}
						onChange={(value) => setAttributes({ colorBackground: value })}
						enableAlpha={true}
						colors={defaultColors}
					/>
				</FlexItem>
				<FlexItem style={{ flexBasis: '50%' }}>
					<span class="fsrg-color-hint"></span>

				</FlexItem>
			</Flex>
			<i>Zeige die naechsten {attributes.tourCount} Rundgaegnge im Karousel</i>
		</div>
	);
}
