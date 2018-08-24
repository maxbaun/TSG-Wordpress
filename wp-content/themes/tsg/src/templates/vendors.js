import React from 'react';
import PropTypes from 'prop-types';

import {innerHtml} from '../utils/wordpressHelpers';
import {phoneLink, emailLink, unique, noop} from '../utils/componentHelpers';
import Button from '../components/button';
import PageWrap from '../components/page';
import FlexibleContent from '../components/flexibleContent';
import ScrollTo from '../components/scrollTo';
import Loading from '../components/loading';
import IsLoadingComponent from '../components/isLoading';
import CSS from '../css/modules/vendors.module.scss';
import Constants from '../constants';

export default class VendorsTemplate extends IsLoadingComponent {
	constructor(props) {
		super(props);

		this.getVendorsInCategory = this.getVendorsInCategory.bind(this);
		this.getCategoryById = this.getCategoryById.bind(this);
	}

	static propTypes = {
		page: PropTypes.object,
		vendors: PropTypes.array,
		categories: PropTypes.object,
		actions: PropTypes.objectOf(PropTypes.func)
	};

	static defaultProps = {
		page: {},
		vendors: [],
		categories: {},
		actions: {noop}
	};

	componentDidMount() {
		if (!this.props.vendors || this.props.vendors.length === 0) {
			this.getVendors();
		}
	}

	getVendorsInCategory(category) {
		const {vendors} = this.props;

		return vendors.filter(vendor => vendor && vendor.vendorCategory && vendor.vendorCategory[0] && vendor.vendorCategory[0].id === category);
	}

	getCategoryById(id) {
		const {categories} = this.props;

		if (!categories.vendors || categories.vendors.length === 0) {
			return;
		}
		const found = categories.vendors.find(c => c.id === id);

		if (!found) {
			return;
		}

		return found;
	}

	getVendors() {
		this.props.actions.vendorsGet({
			fetch: this.fetch
		});
	}

	render() {
		const {page} = this.props;

		const categories = Constants.options.vendorCategories.map(c => c.category[0]);
		const vendorTitle = `Boston's Most Trusted Wedding Vendors`;

		return (
			<PageWrap contain>
				<FlexibleContent page={page}/>
				<div className={CSS.vendorWrap}>
					{this.isLoading() ? (
						<div className={CSS.loader}>
							<Loading color="#42BD95"/>
						</div>
					) : null}
					<div className={CSS.categories}>
						<h3>{vendorTitle}</h3>
						<ul>
							{categories.map(c => {
								const category = this.getCategoryById(c);

								if (!category) {
									return null;
								}

								return (
									<li key={c}>
										<ScrollTo target={`#vendorSection${c}`}>
											{/* eslint-disable-next-line react/no-danger */}
											<p dangerouslySetInnerHTML={innerHtml(category.name)}/>
										</ScrollTo>
									</li>
								);
							})}
						</ul>
					</div>
					<div className={CSS.vendors}>
						{categories.map(categoryId => {
							const category = this.getCategoryById(categoryId);
							const vendors = this.getVendorsInCategory(categoryId);

							if (!category) {
								return null;
							}

							return (
								<div key={categoryId} id={`vendorSection${categoryId}`} className={CSS.vendorSection}>
									{/* eslint-disable-next-line react/no-danger */}
									<h3 dangerouslySetInnerHTML={innerHtml(category.name)}/>
									<ul>
										{vendors.map(vendor => {
											return <li key={vendor.id}>{this.renderVendor(vendor)}</li>;
										})}
									</ul>
								</div>
							);
						})}
					</div>
				</div>
			</PageWrap>
		);
	}

	renderVendor(vendor) {
		const {title} = vendor;
		const {phone, email, link, venuePage} = vendor;
		return (
			<div className={CSS.vendor}>
				{link ? (
					<a href={link} target="_blank" rel="noopener noreferrer">
						<h5
							// eslint-disable-next-line react/no-danger
							dangerouslySetInnerHTML={innerHtml(title)}
						/>
					</a>
				) : (
					<h5
						// eslint-disable-next-line react/no-danger
						dangerouslySetInnerHTML={innerHtml(title)}
					/>
				)}
				<ul className={CSS.vendorDetails}>
					{phone ? (
						<li>
							<span className="fa fa-phone"/>
							<a href={phoneLink(phone)}>{phone}</a>
						</li>
					) : null}
					{email ? (
						<li>
							<span className="fa fa-envelope"/>
							<a href={emailLink(email)}>{email}</a>
						</li>
					) : null}
					{link ? (
						<li>
							<span className="fa fa-keyboard"/>
							<a href={link} target="_blank" rel="noopener noreferrer">
								{link}
							</a>
						</li>
					) : null}
					{venuePage && venuePage.url ? (
						<li style={{marginTop: 10}}>
							<Button to={venuePage} size="xs" style={{display: 'block', width: 105, boxShadow: 'none'}}>
								See Photos
							</Button>
						</li>
					) : null}
				</ul>
			</div>
		);
	}
}
