import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Link} from 'react-router-dom';

import {replaceLinks, isExternalLink} from '../utils/wordpressHelpers';
import CSS from '../css/modules/button.module.scss';

export default class Button extends Component {
	static propTypes = {
		to: PropTypes.string,
		children: PropTypes.node.isRequired,
		classname: PropTypes.string,
		size: PropTypes.string,
		style: PropTypes.object,
		type: PropTypes.string
	};

	static defaultProps = {
		to: null,
		classname: 'primary',
		size: 'md',
		style: {},
		type: 'button'
	};

	render() {
		const {to, classname, children, size, style, type: buttonType} = this.props;

		const btnClass = [CSS.btn, CSS[size], CSS[classname]].join(' ');
		const isExternal = isExternalLink(to);

		if (to) {
			const props = {
				className: btnClass,
				style
			};

			if (isExternal) {
				props.href = to;
			} else {
				props.to = replaceLinks(to);
			}

			return isExternal ? <a {...props}>{children}</a> : <Link {...props}>{children}</Link>;
		}

		return (
			// eslint-disable-next-line react/has-button-type
			<button type={buttonType} className={btnClass} style={style}>
				{children}
			</button>
		);
	}
}
