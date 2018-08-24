import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	DJS_GET: 'DJS_GET',
	DJS_SET: 'DJS_SET'
};

export const actions = {
	djsGet: payload => utils.action(types.DJS_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case types.DJS_SET:
			return state.concat(
				action.payload.filter(v => {
					return !state.find(existing => existing.id === v.id);
				})
			);
		default:
			return state;
	}
};

const getDjs = state => state.djs;

export const selectors = {
	getDjs: createSelector([getDjs], djs => djs)
};
