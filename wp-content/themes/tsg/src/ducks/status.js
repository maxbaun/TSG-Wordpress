import {createSelector} from 'reselect';

// Import * as utils from '../utils/duckHelpers';

export const types = {
	FETCH_REQUEST: 'FETCH_REQUEST',
	FETCH_SUCCESS: 'FETCH_SUCCESS'
};

export const actions = {};

export const initialState = {};

export default (state = initialState, action) => {
	switch (action.type) {
		case types.FETCH_REQUEST:
			state = {
				...state,
				[action.fetch]: {
					loading: true
				}
			};

			return state;
		case types.FETCH_SUCCESS:
			state = {
				...state,
				[action.fetch]: {
					loading: false
				}
			};
			return state;
		default:
			return state;
	}
};

const getStatus = state => state.status;

export const selectors = {
	getStatus: createSelector([getStatus], s => s)
};
