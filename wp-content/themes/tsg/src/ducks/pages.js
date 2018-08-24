import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	PAGE_GET: 'PAGE_GET',
	PAGE_SET: 'PAGE_SET'
};

export const actions = {
	pageGet: payload => utils.action(types.PAGE_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case types.PAGE_SET:
			return state.concat(action.payload);
		default:
			return state;
	}
};

const getPages = state => state.pages;

export const selectors = {
	getPages: createSelector([getPages], p => p)
};
