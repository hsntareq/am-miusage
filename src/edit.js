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
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';


/**
 * Internal dependencies for the API fetch
 */
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { useEffect, useState } from '@wordpress/element';
import { PanelBody, ToggleControl } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const {
		showIdColumn,
		showFirstNameColumn,
		showLastNameColumn,
		showEmailColumn,
		showDateColumn,
	} = attributes;

	useEffect(() => {
		apiFetch({ path: `/amiusage/data` })
			.then(posts => {
				console.log(posts);
				setAttributes({ apiData: posts });
			})
			.catch(error => {
				console.error('Error parsing JSON:', error);
			});
	}, []);

	const handleToggleChange = (column) => {
		setAttributes({ [column]: !attributes[column] });
	};

	const renderTableRows = () => {
		const { apiData } = attributes;

		if (!apiData || apiData.length === 0) {
			return (
				<tr>
					<td colSpan="5">{__('No data available', 'amapi')}</td>
				</tr>
			);
		}

		return apiData.map((dataItem, index) => (
			<tr key={index}>
				{showIdColumn && <td>{dataItem.id}</td>}
				{showFirstNameColumn && <td>{dataItem.first_name}</td>}
				{showLastNameColumn && <td>{dataItem.last_name}</td>}
				{showEmailColumn && <td>{dataItem.email}</td>}
				{showDateColumn && <td>{dataItem.date}</td>}
			</tr>
		));
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody title={__('Table Columns', 'amapi')} initialOpen={true}>
					<ToggleControl
						label="ID"
						checked={showIdColumn}
						onChange={() => handleToggleChange('showIdColumn')}
					/>
					<ToggleControl
						label={__('First Name', 'amapi')}
						checked={showFirstNameColumn}
						onChange={() => handleToggleChange('showFirstNameColumn')}
					/>
					<ToggleControl
						label={__('Last Name', 'amapi')}
						checked={showLastNameColumn}
						onChange={() => handleToggleChange('showLastNameColumn')}
					/>
					<ToggleControl
						label={__('Email', 'amapi')}
						checked={showEmailColumn}
						onChange={() => handleToggleChange('showEmailColumn')}
					/>
					<ToggleControl
						label={__('Date', 'amapi')}
						checked={showDateColumn}
						onChange={() => handleToggleChange('showDateColumn')}
					/>
				</PanelBody>
			</InspectorControls>

			<div className="am-apidata-table">
				<table >
					<thead>
						<tr>
							{showIdColumn && <th>__('ID', 'amapi')</th>}
							{showFirstNameColumn && <th>__('First Name', 'amapi')</th>}
							{showLastNameColumn && <th>__('Last Name', 'amapi')</th>}
							{showEmailColumn && <th>__('Email', 'amapi')</th>}
							{showDateColumn && <th>__('Date', 'amapi')</th>}
						</tr>
					</thead>
					<tbody>{renderTableRows()}</tbody>
				</table>
			</div>
		</div >
	);
}
