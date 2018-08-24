import React, {Component} from 'react';
import PropTypes from 'prop-types';

import Hero from './hero';
import PageDescription from './pageDescription';
import SectionHalf from './sectionHalf';
import SectionReviews from './sectionReviews';
import SectionBios from './sectionBios';
import SectionSlant from './sectionSlant';
import SectionFacts from './sectionFacts';
import SectionFeatured from './sectionFeatured';
import SectionAwards from './sectionAwards';
import SectionServices from './sectionServices';
import FullWidthImage from './fullWidthImage';
import SectionComponents from './sectionComponents';
import SectionPlanning from './sectionPlanning';
import FullWidthContent from './fullWidthContent';
import SectionForm from './sectionForm';

const FlexibleContent = ({page, formContain}) => {
	const children = page.acf.flexibleContent.filter(c => {
		return (
			c.component === 'hero' ||
			c.component === 'pageDescriptionContent' ||
			c.component === 'pageDescriptionImages' ||
			c.component === 'sectionHalf' ||
			c.component === 'sectionReviews' ||
			c.component === 'sectionBios' ||
			c.component === 'sectionAwards' ||
			c.component === 'sectionSlant' ||
			c.component === 'sectionFacts' ||
			c.component === 'sectionFeatured' ||
			c.component === 'sectionServices' ||
			c.component === 'fullWidthImage' ||
			c.component === 'sectionComponents' ||
			c.component === 'sectionPlanning' ||
			c.component === 'fullWidthContent' ||
			c.component === 'sectionForm'
		);
	});

	return (
		<div>
			{children.map((child, index) => {
				if (child.component === 'hero') {
					return (
						<Hero
							// eslint-disable-next-line react/no-array-index-key
							key={index}
							title={child.title}
							subtitle={child.subtitle}
							link={child.link}
							images={child.images}
							video={child.video ? child.video : null}
						/>
					);
				}

				if (child.component === 'pageDescriptionContent') {
					return (
						<PageDescription
							// eslint-disable-next-line react/no-array-index-key
							key={index}
							zIndex={0}
							view="content"
							content={child.content}
							angleBottom={!child.disableAngleBottom}
							video={child.video ? child.video : null}
						/>
					);
				}

				if (child.component === 'pageDescriptionImages') {
					return (
						<PageDescription
							// eslint-disable-next-line react/no-array-index-key
							key={index}
							zIndex={0}
							view="images"
							images={child.images}
							angleBottom={!child.disableAngleBottom}
							video={child.video ? child.video : null}
						/>
					);
				}

				if (child.component === 'sectionHalf') {
					return (
						<SectionHalf
							// eslint-disable-next-line react/no-array-index-key
							key={index}
							zIndex={index}
							verticalCenter={child.verticalCenter}
							left={child.left[0]}
							right={child.right[0]}
						/>
					);
				}

				if (child.component === 'sectionReviews') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionReviews key={index} header={child.header} content={child.content} reviews={child.reviews} link={child.link}/>;
				}

				if (child.component === 'sectionBios') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionBios key={index} header={child.header} content={child.content} bios={child.bios}/>;
				}

				if (child.component === 'sectionSlant') {
					return (
						<SectionSlant
							// eslint-disable-next-line react/no-array-index-key
							key={index}
							id={`sectionSlant${index}`}
							slantDirection={child.slantDirection}
							image={child.image}
							content={child.content}
							slantTop={!child.disableSlantTop}
							slantBottom={!child.disableSlantBottom}
						/>
					);
				}

				if (child.component === 'sectionFacts') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionFacts key={index} title={child.title} facts={child.facts} link={child.link}/>;
				}

				if (child.component === 'sectionFeatured') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionFeatured key={index} image={child.image} content={child.content}/>;
				}

				if (child.component === 'sectionAwards') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionAwards key={index} title={child.title} images={child.images}/>;
				}

				if (child.component === 'sectionServices') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionServices key={index} content={child.content} services={child.services}/>;
				}

				if (child.component === 'fullWidthImage') {
					// eslint-disable-next-line react/no-array-index-key
					return <FullWidthImage key={index} {...child}/>;
				}

				if (child.component === 'sectionComponents') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionComponents key={index} header={child.header} components={child.components}/>;
				}

				if (child.component === 'sectionPlanning') {
					console.log(child);
					// eslint-disable-next-line react/no-array-index-key
					return <SectionPlanning key={index} {...child} header={child.header}/>;
				}

				if (child.component === 'fullWidthContent') {
					// eslint-disable-next-line react/no-array-index-key
					return <FullWidthContent key={index} {...child} content={child.content} maxWidth={parseInt(child.maxWidth, 10)}/>;
				}

				if (child.component === 'sectionForm') {
					// eslint-disable-next-line react/no-array-index-key
					return <SectionForm key={index} {...child} content={child.content} form={child.form} contain={formContain}/>;
				}

				return null;
			})}
		</div>
	);
};

FlexibleContent.propTypes = {
	page: PropTypes.object.isRequired,
	formContain: PropTypes.bool
};

FlexibleContent.defaultProps = {
	formContain: true
};

export default FlexibleContent;
