import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {noop, cancellable} from '../utils/componentHelpers';

export default class Image extends Component {
	constructor(props) {
		super(props);

		this.state = {
			loaded: false
		};

		this.handleImageLoad = this.handleImageLoad.bind(this);
		this.loader = null;
	}

	static propTypes = {
		image: PropTypes.object,
		style: PropTypes.object,
		imgStyle: PropTypes.object,
		onLoad: PropTypes.func,
		circle: PropTypes.bool,
		showPlaceholder: PropTypes.bool
	};

	static defaultProps = {
		image: {},
		style: {},
		imgStyle: {},
		onLoad: noop,
		circle: false,
		showPlaceholder: false
	};

	componentDidMount() {
		this.mounted = true;
		this.loader = cancellable(this.preloadImage(this.props.image.url));
		this.loader.then(this.handleImageLoad);
	}

	componentWillUnmount() {
		this.mounted = false;
		if (this.loader) {
			this.loader.cancel();
		}
	}

	preloadImage(src) {
		if (!src) {
			return Promise.resolve();
		}
		return new Promise((resolve, reject) => {
			this.img = new window.Image();
			this.img.onload = () => {
				if (!this.img) {
					return reject();
				}

				resolve();
			};
			this.img.onerror = () => resolve();
			this.img.src = src;

			if (this.img.complete) {
				return resolve();
			}
		});
	}

	handleImageLoad() {
		if (!this.mounted) {
			return;
		}

		this.setState({loaded: true});
		this.props.onLoad();
	}

	render() {
		const {image, circle} = this.props;
		const {loaded} = this.state;

		if (!image) {
			return null;
		}

		const ratio = (image.height * 100) / image.width;

		return (
			<div
				style={{
					position: 'relative',
					overflow: 'hidden',
					...this.props.style
				}}
			>
				<div
					style={{
						width: '100%',
						paddingBottom: `${ratio}%`
					}}
				/>
				{this.props.showPlaceholder ? (
					<div
						style={{
							backgroundColor: '#C3C3C3',
							width: '100%',
							height: '100%',
							position: 'absolute',
							left: 0,
							right: 0,
							top: 0,
							bottom: 0,
							transition: 'opacity 0.5s',
							opacity: loaded ? 0 : 0.2,
							borderRadius: circle ? '50%' : 0
						}}
					/>
				) : null}
				<img
					src={image.url}
					style={{
						position: 'absolute',
						top: 0,
						left: 0,
						opacity: loaded ? 1 : 0,
						visibility: loaded ? 'visible' : 'hidden',
						transition: 'opacity 0.15s',
						width: '100%',
						height: '100%',
						objectFit: 'cover',
						objectPosition: 'center center',
						...this.props.imgStyle
					}}
				/>
			</div>
		);
	}
}
