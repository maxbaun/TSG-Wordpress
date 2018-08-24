import {call, put, takeLatest} from 'redux-saga/effects';

import {types as venueTypes} from '../ducks/venues';
import {types as metaTypes} from '../ducks/meta';
import {types as statusTypes} from '../ducks/status';
import api from '../services/api';

export function * watchVenues() {
	yield takeLatest(venueTypes.VENUES_GET, venuesGet);
	yield takeLatest(venueTypes.VENUE_GET, venueGet);
}

function * venuesGet({payload: {fetch, data}}) {
	yield [
		put({
			type: statusTypes.FETCH_REQUEST,
			fetch
		})
	];

	const res = yield call(api, {
		method: 'GET',
		route: '/tsg/v1/venues',
		data
	});

	yield [
		put({
			type: metaTypes.META_SET_VENUES,
			payload: res.data.meta
		}),
		put({
			type: venueTypes.VENUES_SET,
			payload: res.data.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}

function * venueGet({payload: {fetch, data}}) {
	yield [
		put({
			type: statusTypes.FETCH_REQUEST,
			fetch
		})
	];

	const res = yield call(api, {
		method: 'GET',
		route: '/tsg/v1/venue',
		data
	});

	yield [
		put({
			type: venueTypes.VENUE_SET,
			payload: res.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
