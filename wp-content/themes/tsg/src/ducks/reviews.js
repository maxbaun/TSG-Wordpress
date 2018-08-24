import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	REVIEW_GET: 'REVIEW_GET',
	REVIEW_SET: 'REVIEW_SET'
};

export const actions = {
	reviewGet: payload => utils.action(types.REVIEW_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	let existingIndex = -1;

	if (action.payload && action.payload.id) {
		existingIndex = state.findIndex(v => v.id === action.payload.id);
	}

	switch (action.type) {
		case types.REVIEW_SET:
			if (existingIndex > -1) {
				state[existingIndex] = action.payload;
			} else {
				state = state.concat([action.payload]);
			}

			return state;
		default:
			return state;
	}
};

const getReviews = state => state.reviews;

export const selectors = {
	getReviews: createSelector([getReviews], review => review)
};
