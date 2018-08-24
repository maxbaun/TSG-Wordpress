import {call, put, takeLatest} from 'redux-saga/effects';

import {types as pageTypes} from '../ducks/pages';
import {types as statusTypes} from '../ducks/status';
import api from '../services/api';

export function * watchPages() {
	yield takeLatest(pageTypes.PAGE_GET, pageGet);
}

function * pageGet({payload: {fetch, data, route}}) {
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
			type: pageTypes.PAGE_SET,
			payload: res.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
