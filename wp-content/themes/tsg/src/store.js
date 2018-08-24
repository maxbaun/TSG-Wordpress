import {createStore, applyMiddleware, compose} from 'redux';
import createSagaMiddleware from 'redux-saga';

import Ducks from './ducks/root';
import Sagas from './sagas/root';

const store = () => {
	const initialState = {};

	const sagaMiddleware = createSagaMiddleware();
	const middlewares = applyMiddleware(sagaMiddleware);
	let composeEnhancers = compose;
	const isDev = process.env.NODE_ENV !== 'production';

	if (
		!isDev && // eslint-disable-line
		window.__REACT_DEVTOOLS_GLOBAL_HOOK__ &&
		Object.keys(window.__REACT_DEVTOOLS_GLOBAL_HOOK__._renderers).length
	) {
		window.__REACT_DEVTOOLS_GLOBAL_HOOK__._renderers = {};
	}

	if (isDev) {
		// eslint-disable-line
		// middlewares.push(createLogger());
		composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
	}

	const DataStore = createStore(Ducks, initialState, composeEnhancers(middlewares));

	sagaMiddleware.run(Sagas);

	return DataStore;
};

export default store;
