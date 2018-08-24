import React from 'react';
import PropTypes from 'prop-types';

import FlexibleContent from '../components/flexibleContent';
import Page from '../components/page';
import SocialIcons from '../components/socialIcons';
import CSS from '../css/modules/contact.module.scss';
import {emailLink, phoneLink} from '../utils/componentHelpers';
import {innerHtml} from '../utils/wordpressHelpers';

import Constants from '../constants';

export default class ContactTemplate extends React.Component {
	static propTypes = {
		page: PropTypes.object.isRequired,
		options: PropTypes.object
	};

	static defaultProps = {
		options: {
			social: {}
		}
	};

	render() {
		const {page} = this.props;

		return (
			<Page contain padding={false}>
				<div className={CSS.wrap}>
					<div className={CSS.content}>
						<FlexibleContent page={page} formContain={false}/>
					</div>
					<div className={CSS.sidebar}>
						<div className={CSS.sidebarBlock}>
							<h3>Connect</h3>
							<div className={CSS.socialIcons}>
								<SocialIcons
									facebook={Constants.options.contactSocial.facebook}
									pinterest={Constants.options.contactSocial.pinterest}
									instagram={Constants.options.contactSocial.instagram}
									vimeo={Constants.options.contactSocial.vimeo}
									youtube={Constants.options.contactSocial.youtube}
								/>
							</div>
						</div>
						<div className={CSS.sidebarBlock}>
							<h3>Find Us</h3>
							<p>{Constants.options.contactFindUsMessage}</p>
							<ul className={CSS.find}>
								<li>
									<span className="fa fa-phone"/>
									<a href={phoneLink(Constants.options.contactPhoneNumber)}>{Constants.options.contactPhoneNumber}</a>
								</li>
								<li>
									<span className="fa fa-fax"/>
									<a href={phoneLink(Constants.options.contactFax)}>{Constants.options.contactFax}</a>
								</li>
								<li>
									<span className="fa fa-envelope"/>
									<a href={emailLink(Constants.options.contactEmail)}>{Constants.options.contactEmail}</a>
								</li>
								<li>
									<span className="fa fa-map-marker-alt"/>
									{/* eslint-disable-next-line react/no-danger */}
									<span dangerouslySetInnerHTML={innerHtml(Constants.options.contactAddress)}/>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</Page>
		);
	}
}
