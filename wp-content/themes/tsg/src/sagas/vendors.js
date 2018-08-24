import {call, put, takeLatest} from 'redux-saga/effects';

import {types as vendorTypes} from '../ducks/vendors';
import {types as statusTypes} from '../ducks/status';
import {types as categoryTypes} from '../ducks/categories';
import api from '../services/api';

export function * watchVendors() {
	yield takeLatest(vendorTypes.VENDORS_GET, vendorsGet);
}

function * vendorsGet({payload: {fetch, data}}) {
	yield [
		put({
			type: statusTypes.FETCH_REQUEST,
			fetch
		})
	];

	const res = yield call(api, {
		method: 'GET',
		route: '/tsg/v1/vendors',
		data
	});

	yield [
		put({
			type: categoryTypes.CATEGORIES_SET,
			key: 'vendors',
			payload: res.data.categories
		}),
		put({
			type: vendorTypes.VENDORS_SET,
			payload: res.data.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
