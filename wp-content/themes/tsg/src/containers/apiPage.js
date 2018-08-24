import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';

import {actions as venueActions, selectors as venueSelectors} from '../ducks/venues';
import {actions as postActions, selectors as postSelectors} from '../ducks/posts';
import {actions as pageActions, selectors as pageSelectors} from '../ducks/pages';
import {actions as vendorActions, selectors as vendorSelectors} from '../ducks/vendors';
import {actions as djActions, selectors as djSelectors} from '../ducks/djs';
import {actions as reviewActions, selectors as reviewSelectors} from '../ducks/reviews';
import {selectors as metaSelectors} from '../ducks/meta';
import {selectors as statusSelectors} from '../ducks/status';
import {selectors as categorySelectors} from '../ducks/categories';

import NotFound from '../components/404';
import PageLoader from '../components/pageLoader';
import Page from '../templates/page';
import Contact from '../templates/contact';
import Venues from '../templates/venues';
import Venue from '../templates/venue';
import Vendors from '../templates/vendors';
import Dj from '../templates/dj';
import Review from '../templates/review';
import Availability from '../templates/availability';
import Post from '../templates/post';
import CSS from '../css/modules/apiPage.scss';
import {noop, unique, isLoading} from '../utils/componentHelpers';

// Const hasOwnProperty = Object.prototype.hasOwnProperty;

class ApiPage extends Component {
	constructor(props) {
		super(props);

		this.getData = this.getData.bind(this);
		this.getCurrentData = this.getCurrentData.bind(this);
		this.renderContent = this.renderContent.bind(this);

		this.fetch = unique();
	}

	static propTypes = {
		match: PropTypes.object.isRequired,
		apiRoute: PropTypes.string.isRequired,
		component: PropTypes.oneOfType([PropTypes.func, PropTypes.bool]),
		actions: PropTypes.objectOf(PropTypes.func),
		pages: PropTypes.array,
		status: PropTypes.object,
		dataSet: PropTypes.string.isRequired,
		venues: PropTypes.array,
		reviews: PropTypes.array,
		djs: PropTypes.array
	};

	static defaultProps = {
		component: false,
		actions: {noop},
		pages: [],
		venues: [],
		status: {},
		reviews: [],
		djs: []
	};

	componentDidMount() {
		if (!this.getCurrentData()) {
			this.getData();
		}
	}

	shouldComponentUpdate(nextProps) {
		return nextProps.status !== this.props.status;
	}

	isLoading() {
		return isLoading(this.props.status, this.fetch);
	}

	getData() {
		const {dataSet} = this.props;

		if (dataSet === 'venue') {
			return this.getVenue();
		}

		if (dataSet === 'dj') {
			return this.getDjs();
		}

		if (dataSet === 'review') {
			return this.getReview();
		}

		if (dataSet === 'post') {
			return this.getPosts();
		}

		return this.getPage();
	}

	getVenue() {
		this.props.actions.venueGet({
			fetch: this.fetch,
			route: this.props.apiRoute,
			data: {
				slug: this.getSlug()
			}
		});
	}

	getPage() {
		this.props.actions.pageGet({
			fetch: this.fetch,
			route: this.props.apiRoute,
			data: {
				slug: this.getSlug()
			}
		});
	}

	getDjs() {
		this.props.actions.djsGet({
			fetch: this.fetch,
			route: this.props.apiRoute,
			data: {
				slug: this.getSlug()
			}
		});
	}

	getReview() {
		this.props.actions.reviewGet({
			fetch: this.fetch,
			route: this.props.apiRoute,
			data: {
				slug: this.getSlug()
			}
		});
	}

	getPosts() {
		const data = {};
		const slug = this.getSlug();
		if (slug) {
			data.slug = slug;
		}

		this.props.actions.postsGet({
			fetch: this.fetch,
			route: this.props.apiRoute,
			data
		});
	}

	getSlug() {
		return this.props.match.params && this.props.match.params.slug ? this.props.match.params.slug : 'home';
	}

	getCurrentData() {
		const {dataSet} = this.props;

		if (dataSet === 'venue') {
			return this.getCurrentVenue();
		}

		if (dataSet === 'dj') {
			return this.getCurrentDj();
		}

		if (dataSet === 'review') {
			return this.getCurrentReview();
		}

		if (dataSet === 'post') {
			return this.getCurrentPost();
		}

		return this.getCurrentPage();
	}

	getCurrentVenue() {
		const {venues} = this.props;

		if (!venues || venues.length === 0) {
			return;
		}

		return venues.find(p => p.slug === this.getSlug());
	}

	getCurrentDj() {
		const {djs} = this.props;

		if (!djs || djs.length === 0) {
			return;
		}

		return djs.find(p => p.slug === this.getSlug());
	}

	getCurrentReview() {
		const {reviews} = this.props;

		if (!reviews || reviews.length === 0) {
			return;
		}

		return reviews.find(p => p.slug === this.getSlug());
	}

	getCurrentPost() {
		const {posts} = this.props;

		if (!posts || posts.length === 0) {
			return;
		}

		return posts.find(p => p.slug === this.getSlug());
	}

	getCurrentPage() {
		const {pages} = this.props;

		if (!pages || pages.length === 0) {
			return;
		}

		return pages.find(p => p && p.slug === this.getSlug());
	}

	render() {
		let content = null;

		if (!this.isLoading() && !this.getCurrentData()) {
			content = <NotFound/>;
		} else {
			content = this.renderContent();
		}

		return (
			<div className={CSS.page}>
				<PageLoader active={this.isLoading()}/>
				{content}
			</div>
		);
	}

	renderContent() {
		const {component: Comp} = this.props;
		const venue = this.getCurrentVenue();
		const page = this.getCurrentPage();
		const dj = this.getCurrentDj();
		const review = this.getCurrentReview();
		const post = this.getCurrentPost();
		const props = {
			...this.props,
			page,
			venue,
			dj,
			review,
			post
		};

		if (Comp) {
			return <Comp {...props}/>;
		}

		if (!page && !venue && !dj && !review && !post) {
			return null;
		}

		if (venue) {
			return <Venue {...props}/>;
		}

		if (dj) {
			return <Dj {...props}/>;
		}

		if (review) {
			return <Review {...props}/>;
		}

		if (post) {
			return <Post {...props}/>;
		}

		if (page.template === 'template-contact.php') {
			return <Contact {...props}/>;
		}

		if (page.template === 'template-venues.php') {
			return <Venues {...props}/>;
		}

		if (page.template === 'template-vendors.php') {
			return <Vendors {...props}/>;
		}

		if (page.template === 'template-availability.php') {
			return <Availability {...props}/>;
		}

		return <Page {...props}/>;
	}
}

const mapStateToProps = state => ({
	venues: venueSelectors.getVenues(state),
	meta: metaSelectors.getMeta(state),
	status: statusSelectors.getStatus(state),
	pages: pageSelectors.getPages(state),
	vendors: vendorSelectors.getVendors(state),
	categories: categorySelectors.getCategories(state),
	reviews: reviewSelectors.getReviews(state),
	posts: postSelectors.getPosts(state),
	djs: djSelectors.getDjs(state)
});

const mapDispatchToProps = dispatch => ({
	actions: bindActionCreators(
		{
			...venueActions,
			...pageActions,
			...vendorActions,
			...djActions,
			...reviewActions,
			...postActions
		},
		dispatch
	)
});

export default connect(
	mapStateToProps,
	mapDispatchToProps
)(ApiPage);
