import 'babel-polyfill';

import React, {Component, Fragment} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Route, Switch, Link, Redirect, withRouter} from 'react-router-dom';
import {TransitionGroup, CSSTransition} from 'react-transition-group';
import {Provider} from 'react-redux';

import store from './store';
import App from './containers/app';

const Index = () => {
	return (
		<Provider store={store()}>
			<BrowserRouter>
				<Route component={App}/>
			</BrowserRouter>
		</Provider>
	);
};

const AnimationExample = () => (
	<BrowserRouter>
		<Route
			render={({location}) => (
				<div style={styles.fill}>
					<Route exact path="/" render={() => <Redirect to="/hsl/10/90/50"/>}/>

					<ul style={styles.nav}>
						<NavLink to="/boston-wedding-djs">Red</NavLink>
						<NavLink to="/boston-wedding-lighting">Green</NavLink>
						<NavLink to="/page-3">Blue</NavLink>
						<NavLink to="/page-4">Pink</NavLink>
					</ul>

					<div style={styles.content}>
						<TransitionGroup>
							{/* no different than other usage of
				  CSSTransition, just make sure to pass
				  `location` to `Switch` so it can match
				  the old location as it animates out
			  */}
							<CSSTransition key={location.key} classNames="fade" timeout={300}>
								<Switch location={location}>
									<Route path="/:id" component={Page}/>
									{/* Without this `Route`, we would get errors during
					  the initial transition from `/` to `/hsl/10/90/50`
				  */}
									<Route render={() => <div>Not Found</div>}/>
								</Switch>
							</CSSTransition>
						</TransitionGroup>
					</div>
				</div>
			)}
		/>
	</BrowserRouter>
);

const TestComponent = props => (
	<div
		style={{
			...styles.fill
		}}
	>
		<h1>{props.match.params.id}</h1>
	</div>
);

const NavLink = props => (
	<li style={styles.navItem}>
		<Link {...props} style={{color: 'inherit'}}/>
	</li>
);

const HSL = ({match: {params}}) => (
	<div
		style={{
			...styles.fill,
			...styles.hsl,
			background: `hsl(${params.h}, ${params.s}%, ${params.l}%)`
		}}
	>
		hsl(
		{params.h}, {params.s}
		%, {params.l}
		%)
	</div>
);

const RGB = ({match: {params}}) => (
	<div
		style={{
			...styles.fill,
			...styles.rgb,
			background: `rgb(${params.r}, ${params.g}, ${params.b})`
		}}
	>
		rgb(
		{params.r}, {params.g}, {params.b})
	</div>
);

const styles = {};

styles.fill = {
	position: 'absolute',
	left: 0,
	right: 0,
	top: 0,
	bottom: 0
};

styles.content = {
	...styles.fill,
	top: '40px',
	textAlign: 'center'
};

styles.nav = {
	padding: 0,
	margin: 0,
	position: 'absolute',
	top: 0,
	height: '40px',
	width: '100%',
	display: 'flex'
};

styles.navItem = {
	textAlign: 'center',
	flex: 1,
	listStyleType: 'none',
	padding: '10px'
};

styles.hsl = {
	...styles.fill,
	color: 'white',
	paddingTop: '20px',
	fontSize: '30px'
};

styles.rgb = {
	...styles.fill,
	color: 'white',
	paddingTop: '20px',
	fontSize: '30px'
};

ReactDOM.render(<Index/>, document.getElementById('app'));
