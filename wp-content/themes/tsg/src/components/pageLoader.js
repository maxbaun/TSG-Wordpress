import React from 'react';
import PropTypes from 'prop-types';

import Loader from './loading';
import CSS from '../css/modules/pageLoader.scss';

const PageLoader = ({active}) => {
	const wrapCss = [CSS.pageLoader];

	if (active === false) {
		wrapCss.push([CSS.pageLoaderHidden]);
	}
	return (
		<div className={wrapCss.join(' ')}>
			<div className={CSS.inner}>
				<Loader color="#42BD95"/>
			</div>
		</div>
	);
};

PageLoader.propTypes = {
	active: PropTypes.bool
};

PageLoader.defaultProps = {
	active: false
};

export default PageLoader;
