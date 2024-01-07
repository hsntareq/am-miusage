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
export default function Edit({ attributes, setAttributes }) {

	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchData = async () => {
			try {
				const response = await fetch(props.attributes.apiEndpoint);
				const data = await response.json();
				// Update block attributes with fetched data
				props.setAttributes({ data, loading: false });
			} catch (error) {
				console.error('Error fetching data:', error);
				setLoading(false);
			}
		};

		fetchData();
	}, [props.attributes.apiEndpoint]);

	return (
		<p {...useBlockProps()}>
			{__('Am Apiblock – hello from the editor!', 'am-apiblock')}
			<TextControl
				label="API Endpoint"
				value={props.attributes.apiEndpoint}
				onChange={(value) => props.setAttributes({ apiEndpoint: value })}
			/>
			{loading ? (
				<p>Loading...</p>
			) : (
				<table>
					<thead>
						<tr>
							<th>Column 1</th>
							<th>Column 2</th>
							{/* Add more columns as needed */}
						</tr>
					</thead>
					<tbody>
						{props.attributes.data.map((item, index) => (
							<tr key={index}>
								<td>{item.column1}</td>
								<td>{item.column2}</td>
								{/* Map more columns based on your API response structure */}
							</tr>
						))}
					</tbody>
				</table>
			)}
		</p>
	);
}
