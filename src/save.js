/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {Element} Element to render.
 */
export default function save({ attributes }) {
	const { showIdColumn, showFirstNameColumn, showLastNameColumn, showEmailColumn, showDateColumn } = attributes;

	const renderTableRows = () => {
		const { apiData } = attributes;

		if (!apiData || apiData.length === 0) {
			return (
				<tr>
					<td colSpan="5">No data available</td>
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
		<div {...useBlockProps.save()}>
			<table className='am-apidata-table'>
				<thead>
					<tr>
						{showIdColumn && <th>ID</th>}
						{showFirstNameColumn && <th>First Name</th>}
						{showLastNameColumn && <th>Last Name</th>}
						{showEmailColumn && <th>Email</th>}
						{showDateColumn && <th>Date</th>}
					</tr>
				</thead>
				<tbody>{renderTableRows()}</tbody>
			</table>
		</div>
	);
}


