import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Route} from 'react-router-dom';

import ApiPage from './apiPage';

export default class ApiRoute extends Component {
	static propTypes = {
		apiRoute: PropTypes.string.isRequired,
		dataSet: PropTypes.string.isRequired,
		component: PropTypes.oneOfType([PropTypes.func, PropTypes.bool])
	};

	static defaultProps = {
		component: false
	};

	render() {
		const routeProps = {...this.props};
		delete routeProps.component;

		return (
			<Route
				{...routeProps}
				render={props => {
					return React.createElement(ApiPage, {
						...props,
						dataSet: this.props.dataSet,
						apiRoute: this.props.apiRoute,
						component: this.props.component
					});
				}}
			/>
		);
	}
}
