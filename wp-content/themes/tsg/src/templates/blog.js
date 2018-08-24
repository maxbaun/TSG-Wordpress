import React from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import InfiniteScroll from 'react-infinite-scroller';

import FlexibleContent from '../components/flexibleContent';
import Image from '../components/image';
import Link from '../components/link';
import Loader from '../components/loading';
import CSS from '../css/modules/venues.module.scss';
import {innerHtml} from '../utils/wordpressHelpers';
import {noop, unique, isLoading} from '../utils/componentHelpers';

export default class VenueTemplate extends React.Component {
	constructor(props) {
		super(props);

		this.handleLoadMore = this.handleLoadMore.bind(this);
		this.isLoading = this.isLoading.bind(this);
		this.hasMore = this.hasMore.bind(this);

		this.fetch = unique();
	}

	static propTypes = {
		page: PropTypes.object.isRequired,
		location: PropTypes.object.isRequired,
		actions: PropTypes.objectOf(PropTypes.func),
		[p]: PropTypes.array,
		status: PropTypes.object,
		meta: PropTypes.object
	};

	static defaultProps = {
		actions: {noop},
		[p]: [],
		status: {},
		meta: {}
	};

	componentDidMount() {
		this.getVenues(1);
	}

	isLoading() {
		return isLoading(this.props.status, this.fetch);
	}

	hasMore() {
		return this.props.meta.venues && this.props.meta.venues.hasMore;
	}

	getVenues(page = 1) {
		if (this.isLoading()) {
			return;
		}

		this.props.actions.venuesGet({
			fetch: this.fetch,
			data: {
				page
			}
		});
	}

	handleLoadMore(page) {
		this.getVenues(page);
	}

	render() {
		const {page, venues} = this.props;

		return (
			<div>
				<FlexibleContent page={page}/>
				<InfiniteScroll
					initialLoad={false}
					pageStart={1}
					hasMore={this.isLoading() === false && this.hasMore()}
					loadMore={this.handleLoadMore}
					loader={
						<div key={0} className={CSS.loader}>
							<Loader color="#7A246F"/>
						</div>
					}
				>
					<div className={CSS.venues}>
						<ul>
							{venues.map(venue => {
								return (
									<li key={venue.id}>
										<Link to={`/venue/${venue.slug}`} classname={CSS.venue}>
											<div className={CSS.venueImage}>
												<Image image={venue.image} style={{height: '100%'}}/>
											</div>
											<div className={CSS.venueTitle}>
												{/* eslint-disable-next-line react/no-danger */}
												<h3 dangerouslySetInnerHTML={innerHtml(venue.title)}/>
											</div>
										</Link>
									</li>
								);
							})}
						</ul>
					</div>
				</InfiniteScroll>
			</div>
		);
	}
}
