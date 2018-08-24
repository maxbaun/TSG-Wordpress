import {call, put, takeLatest} from 'redux-saga/effects';

import {types as reviewTypes} from '../ducks/reviews';
import {types as statusTypes} from '../ducks/status';
import api from '../services/api';

export function * watchReviews() {
	yield takeLatest(reviewTypes.REVIEW_GET, reviewGet);
}

function * reviewGet({payload: {fetch, data, route}}) {
	yield [
		put({
			type: statusTypes.FETCH_REQUEST,
			fetch
		})
	];

	const res = yield call(api, {
		method: 'GET',
		route,
		data
	});

	yield [
		put({
			type: reviewTypes.REVIEW_SET,
			payload: res.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
