import {call, put, takeLatest} from 'redux-saga/effects';

import {types as djTypes} from '../ducks/djs';
import {types as statusTypes} from '../ducks/status';
import api from '../services/api';

export function * watchDjs() {
	yield takeLatest(djTypes.DJS_GET, djGet);
}

function * djGet({payload: {fetch, data, route}}) {
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
			type: djTypes.DJS_SET,
			payload: res.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
