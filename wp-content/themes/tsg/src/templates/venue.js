import React from 'react';
import PropTypes from 'prop-types';

import CSS from '../css/modules/venue.module.scss';
import Page from '../components/page';
import SectionContent from '../components/sectionContent';
import Gallery from '../components/gallery';
import Button from '../components/button';
import IsLoadingComponent from '../components/isLoading';
import {noop} from '../utils/componentHelpers';
import Constants from '../constants';

export default class VenueTemplate extends IsLoadingComponent {
	static propTypes = {
		venue: PropTypes.object,
		actions: PropTypes.objectOf(PropTypes.func),
		match: PropTypes.object.isRequired,
		status: PropTypes.object
	};

	static defaultProps = {
		venue: {},
		actions: {noop},
		status: {}
	};

	componentDidMount() {
		if (this.props.venue && !this.props.venue.gallery) {
			this.getVenuePhotos();
		}
	}

	getVenuePhotos() {
		this.props.actions.venueGet({
			fetch: this.fetch,
			data: {
				slug: this.props.match.params.slug
			}
		});
	}

	render() {
		const {venue} = this.props;

		let header = `<h1>${venue.title}</h1>`;

		if (venue.location) {
			header += `<h3>${venue.location}</h3>`;
		}

		return (
			<Page contain>
				<SectionContent classname="venueTemplate" content={{header}}/>
				<Gallery loading={this.isLoading()} images={venue.gallery || []}/>
				<div className={CSS.cta}>
					<Button to={Constants.options.allVenuesLink.url}>{Constants.options.allVenuesLink.title}</Button>
				</div>
			</Page>
		);
	}
}
