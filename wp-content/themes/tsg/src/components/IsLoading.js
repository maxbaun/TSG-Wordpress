import {Component} from 'react';
import PropTypes from 'prop-types';

import {unique, isLoading} from '../utils/componentHelpers';

export default class IsLoading extends Component {
	constructor(props) {
		super(props);
		this.fetch = unique();
	}

	static propTypes = {
		status: PropTypes.object.isRequired
	};

	isLoading() {
		return isLoading(this.props.status, this.fetch);
	}
}
