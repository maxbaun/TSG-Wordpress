import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Switch} from 'react-router-dom';
import {TransitionGroup, CSSTransition} from 'react-transition-group';

import '../css/plugins/normalize.css';
import '../css/plugins/icomoon/style.css';
import '../css/plugins/bootstrap-grid.css';
import '../css/plugins/fontawesome/css/all.css';
import '../css/plugins/simple-line-icons/css/simple-line-icons.css';
import '../css/plugins/swiper.css';
import '../css/utils/global.scss';

import ScrollToTop from '../components/scrollToTop';
import Header from '../components/header';
import Footer from '../components/footer';
import ApiRoute from './apiRoute';

class App extends Component {
	static propTypes = {
		location: PropTypes.object.isRequired
	};

	render() {
		const {location} = this.props;
		return (
			<ScrollToTop location={location}>
				<Header/>
				<Header sticky/>
				<TransitionGroup className="page-wrap">
					<CSSTransition key={location.pathname} classNames="fade" timeout={300}>
						<Switch location={location}>
							<ApiRoute dataSet="venue" apiRoute="tsg/v1/venue" path="/venue/:slug"/>
							<ApiRoute dataSet="dj" apiRoute="tsg/v1/djs" path="/dj/:slug"/>
							<ApiRoute dataSet="review" apiRoute="tsg/v1/reviews" path="/review/:slug"/>
							<ApiRoute dataSet="post" apiRoute="tsg/v1/posts" path="/blog/:slug"/>
							<ApiRoute dataSet="page" apiRoute="tsg/v1/page" path="/:slug"/>
							<ApiRoute exact dataSet="page" apiRoute="tsg/v1/page?slug=home" path="/"/>
						</Switch>
					</CSSTransition>
				</TransitionGroup>
				<Footer/>
			</ScrollToTop>
		);
	}
}

export default App;
