import React, {Fragment} from 'react';
import PropTypes from 'prop-types';

import SectionCta from '../components/sectionCta';
import FlexibleContent from '../components/flexibleContent';

export default class PageTemplate extends React.Component {
	static propTypes = {
		location: PropTypes.object.isRequired,
		page: PropTypes.object
	};

	static defaultProps = {
		page: {}
	};

	render() {
		const {page} = this.props;

		return (
			<Fragment>
				{page ? <FlexibleContent page={page}/> : null}
				{page && page.acf.hasCallToAction ? <SectionCta/> : null}
			</Fragment>
		);
	}
}
