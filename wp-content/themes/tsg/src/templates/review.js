import React from 'react';
import PropTypes from 'prop-types';

import Page from '../components/page';
import SectionContent from '../components/sectionContent';
import Image from '../components/image';
import Button from '../components/button';
import CSS from '../css/modules/review.module.scss';
import {innerHtml} from '../utils/wordpressHelpers';
import Constants from '../constants';

export default class ReviewTemplate extends React.Component {
	static propTypes = {
		review: PropTypes.object
	};

	static defaultProps = {
		review: {}
	};

	render() {
		const {review} = this.props;

		let ratingArr = [];

		for (let index = 0; index < parseInt(review.rating, 10); index++) {
			ratingArr.push({id: index});
		}

		return (
			<Page contain>
				<div className={CSS.wrap}>
					<SectionContent
						classname="review"
						content={{
							header: `<h1>${review.title}</h1>`
						}}
					/>
					<div className={CSS.review}>
						<div className={CSS.reviewHeader}>
							<div className={CSS.reviewDetails}>
								<ul>
									<li>
										<h3>Rating</h3>
										<div className={CSS.rating}>
											{ratingArr.map(r => {
												return <span key={r.id}/>;
											})}
										</div>
									</li>
									<li>
										<h3>Location</h3>
										{review.location}
									</li>
									<li>
										<h3>Services</h3>
										<span>{review.services}</span>
									</li>
								</ul>
							</div>
						</div>
						{/* eslint-disable-next-line react/no-danger */}
						<div className={CSS.reviewBody} dangerouslySetInnerHTML={innerHtml(review.content)}/>
					</div>
					<div className={CSS.cta}>
						<Button to={Constants.options.allReviewsLink}>See All Reviews!</Button>
					</div>
				</div>
			</Page>
		);
	}
}
