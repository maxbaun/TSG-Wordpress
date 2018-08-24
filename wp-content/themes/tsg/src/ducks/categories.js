import {createSelector} from 'reselect';

// Import * as utils from '../utils/duckHelpers';

export const types = {
	CATEGORIES_SET: 'CATEGORIES_SET'
};

export const actions = {};

export const initialState = {
	vendors: []
};

export default (state = initialState, action) => {
	switch (action.type) {
		case types.CATEGORIES_SET:
			state = {
				...state,
				[action.key]: action.payload
			};

			return state;
		default:
			return state;
	}
};

const getCategories = state => state.categories;

export const selectors = {
	getCategories: createSelector([getCategories], categories => categories)
};
