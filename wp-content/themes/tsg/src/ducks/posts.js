import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	POSTS_GET: 'POSTS_GET',
	POSTS_SET: 'POSTS_SET'
};

export const actions = {
	postsGet: payload => utils.action(types.POSTS_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	switch (action.type) {
		case types.POSTS_SET:
			return state.concat(
				action.payload.filter(v => {
					return !state.find(existing => existing.id === v.id);
				})
			);
		default:
			return state;
	}
};

const getPosts = state => state.posts;

export const selectors = {
	getPosts: createSelector([getPosts], posts => posts)
};
