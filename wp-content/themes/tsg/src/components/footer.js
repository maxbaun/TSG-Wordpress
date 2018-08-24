import React, {Component} from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

import Logo from '../img/tsg-logo-light.png';
import CSS from '../css/modules/footer.module.scss';
import {phoneLink, emailLink} from '../utils/componentHelpers';
import Link from './link';
import WindowSize from './windowSize';
import SocialIcons from './socialIcons';

import Constants from '../constants';

class Footer extends Component {
	constructor(props) {
		super(props);

		this.state = {
			menus: []
		};
	}

	componentDidMount() {
		this.getMenues();
	}

	getMenues() {
		axios
			.get('http://tsg.info/wp-json/tsg/v1/menu/footer')
			.then(res => res.data.data)
			.then(menus => {
				this.setState({menus});
			});
	}

	render() {
		return (
			<footer className={CSS.footer}>
				<div className="container">
					<div className={CSS.inner}>
						<div className={CSS.description}>
							<div className={CSS.descriptionInner}>
								<div className={CSS.logo}>
									<Link to="/">
										<img src={Logo} alt="TSG Weddings Logo" width={181} height={59}/>
									</Link>
								</div>
								<p>{Constants.options.footerDescription}</p>
							</div>
						</div>
						{this.state.menus &&
							this.state.menus.map(menu => {
								return (
									<div key={menu.title} className={CSS.menu}>
										<h3>{menu.title}</h3>
										<ul>
											{menu.items &&
												menu.items.map(item => {
													return (
														<li key={item.title}>
															<Link to={item.url} classname={CSS.menuLink}>
																{item.title}
															</Link>
														</li>
													);
												})}
										</ul>
									</div>
								);
							})}
						<div className={[CSS.menu, CSS.social].join(' ')}>
							<h3>Connect With Us</h3>
							<ul>
								<li>
									<a href={phoneLink(Constants.options.contactPhone)} className={CSS.menuLink}>
										{Constants.options.contactPhone}
									</a>
								</li>
								<li>
									<a href={emailLink(Constants.options.contactEmail)} className={CSS.menuLink}>
										{Constants.options.contactEmail}
									</a>
								</li>
							</ul>
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
					</div>
				</div>
				<div className={CSS.copy}>
					<div className="container">
						<p>© TSG Weddings // The Sussman Group, LLC</p>
					</div>
				</div>
			</footer>
		);
	}

	renderIcon(link, icon) {
		if (!link || link === '') {
			return null;
		}

		return (
			<li>
				<a className={CSS.icon} href={link} target="_blank" rel="noopener noreferrer">
					<span className={`fab fa-${icon}`}/>
				</a>
			</li>
		);
	}
}

export default WindowSize(Footer);
