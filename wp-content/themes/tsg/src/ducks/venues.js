import {createSelector} from 'reselect';

import * as utils from '../utils/duckHelpers';

export const types = {
	VENUES_GET: 'VENUES_GET',
	VENUES_SET: 'VENUES_SET',
	VENUE_GET: 'VENUE_GET',
	VENUE_SET: 'VENUE_SET'
};

export const actions = {
	venuesGet: payload => utils.action(types.VENUES_GET, {payload}),
	venueGet: payload => utils.action(types.VENUE_GET, {payload})
};

export const initialState = [];

export default (state = initialState, action) => {
	let existingIndex = -1;

	if (action.payload && action.payload.id) {
		existingIndex = state.findIndex(v => v.id === action.payload.id);
	}

	switch (action.type) {
		case types.VENUES_SET:
			return state.concat(
				action.payload.filter(v => {
					return !state.find(existing => existing.id === v.id);
				})
			);
		case types.VENUE_SET:
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

const getVenues = state => state.venues;

export const selectors = {
	getVenues: createSelector([getVenues], venues => {
		if (!venues || venues.length === 0) {
			return [];
		}

		const sortedVenues = venues.sort((a, b) => {
			const aTitle = a.title.toLowerCase();
			const bTitle = b.title.toLowerCase();

			if (aTitle < bTitle) {
				return -1;
			}

			if (aTitle > bTitle) {
				return 1;
			}

			return 0;
		});

		return sortedVenues;
	})
};
