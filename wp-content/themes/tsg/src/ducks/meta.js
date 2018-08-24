import {createSelector} from 'reselect';

// Import * as utils from '../utils/duckHelpers';

export const types = {
	META_SET_VENUES: 'META_SET_VENUES',
	META_SET_POSTS: 'META_SET_POSTS'
};

export const actions = {};

export const initialState = {
	venues: {},
	posts: {}
};

export default (state = initialState, action) => {
	switch (action.type) {
		case types.META_SET_VENUES:
			state.venues = action.payload;
			state.venues.hasMore = action.payload.currentPage < action.payload.totalPages;
			return state;
		case types.META_SET_POSTS:
			state.posts = action.payload;
			state.posts.hasMore = action.payload.currentPage < action.payload.totalPages;
			return state;
		default:
			return state;
	}
};

const getMeta = state => state.meta;

export const selectors = {
	getMeta: createSelector([getMeta], v => v)
};
