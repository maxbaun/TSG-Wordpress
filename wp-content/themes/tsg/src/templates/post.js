import React, {Component} from 'react';
import PropTypes from 'prop-types';

import Page from '../components/page';
import Image from '../components/image';
import {innerHtml} from '../utils/wordpressHelpers';
import CSS from '../css/modules/post.scss';

export default class PostTemplate extends Component {
	constructor(props) {
		super(props);

		this.state = {
			heroActive: false
		};

		this.handleImageLoad = this.handleImageLoad.bind(this);
	}

	static propTypes = {
		location: PropTypes.object.isRequired,
		post: PropTypes.object
	};

	static defaultProps = {
		post: {}
	};

	handleImageLoad() {
		this.setState({heroActive: true});
	}

	render() {
		const {post} = this.props;

		const heroCss = [CSS.hero];

		if (this.state.heroActive) {
			heroCss.push(CSS.heroActive);
		}

		return (
			<Page padding={false}>
				{post.image ? (
					<div className={heroCss.join(' ')}>
						<div className={CSS.heroImage}>
							<Image
								placeholder
								image={post.image}
								onLoad={this.handleImageLoad}
								style={{height: '100%'}}
								imgStyle={{height: '100%'}}
							/>
						</div>
						<div className={CSS.heroContent}>
							<h1>{post.title}</h1>
						</div>
					</div>
				) : (
					<div className={CSS.header}>
						<h1>{post.title}</h1>
					</div>
				)}
				<div className={CSS.post}>
					{/* eslint-disable-next-line react/no-danger */}
					<div dangerouslySetInnerHTML={innerHtml(post.content)}/>
				</div>
			</Page>
		);
	}
}
