import React from 'react';
import PropTypes from 'prop-types';

import DJThumbnail from '../components/djThumbnail';
import Image from '../components/image';
import Link from '../components/link';
import SectionContent from '../components/sectionContent';
import Page from '../components/page';
import {djLink} from '../utils/linkHelpers';
import {bioPosition, bioName, innerHtml} from '../utils/wordpressHelpers';
import CSS from '../css/modules/dj.module.scss';

export default class DJTemplate extends React.Component {
	static propTypes = {
		djs: PropTypes.array,
		dj: PropTypes.object
	};

	static defaultProps = {
		djs: [],
		dj: {}
	};

	getOtherDjs() {
		const {dj, djs} = this.props;
		if (!dj || !djs || djs.length === 0) {
			return;
		}

		return djs.filter(d => d.slug !== dj.slug);
	}

	render() {
		const {djs: otherDjs, dj} = this.props;

		return (
			<Page contain>
				<div className={CSS.wrap}>
					<div className={CSS.dj}>
						<div className={CSS.image}>
							<DJThumbnail image={dj.image}/>
						</div>
						{/* eslint-disable-next-line react/no-danger */}
						<h1 dangerouslySetInnerHTML={innerHtml(bioName(dj.title))}/>
						<h3>{bioPosition(dj.title)}</h3>
						{/* eslint-disable-next-line react/no-danger */}
						<div dangerouslySetInnerHTML={innerHtml(dj.content)} className={CSS.content}/>
					</div>
					<div className={CSS.other}>
						<SectionContent
							classname="otherDjs"
							content={{
								header: `<h2>Our Boston Wedding DJs</h2>`
							}}
						/>
						<ul>
							{otherDjs.map(dj => {
								return (
									<li key={dj.title}>
										<div className={CSS.otherDJ}>
											<Link to={djLink(dj.slug)}>
												<div className={CSS.otherDJImage}>
													<Image image={dj.image}/>
												</div>
												<div className={CSS.otherDJOverlay}>
													{/* eslint-disable-next-line react/no-danger */}
													<h5 dangerouslySetInnerHTML={innerHtml(bioName(dj.title))}/>
												</div>
											</Link>
										</div>
									</li>
								);
							})}
						</ul>
					</div>
				</div>
			</Page>
		);
	}
}
