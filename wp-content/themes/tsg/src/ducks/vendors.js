import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	VENDORS_GET: 'VENDORS_GET',
	VENDORS_SET: 'VENDORS_SET'
};

export const actions = {
	vendorsGet: payload => utils.action(types.VENDORS_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case types.VENDORS_SET:
			return state.concat(
				action.payload.filter(v => {
					return !state.find(existing => existing.id === v.id);
				})
			);
		default:
			return state;
	}
};

const getVendors = state => state.vendors;

export const selectors = {
	getVendors: createSelector([getVendors], vendors => vendors)
};
