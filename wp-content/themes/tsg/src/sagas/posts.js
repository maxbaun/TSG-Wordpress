import {call, put, takeLatest} from 'redux-saga/effects';

import {types as postTypes} from '../ducks/posts';
import {types as metaTypes} from '../ducks/meta';
import {types as statusTypes} from '../ducks/status';
import api from '../services/api';

export function * watchPosts() {
	yield takeLatest(postTypes.POSTS_GET, postsGet);
}

function * postsGet({payload: {fetch, data}}) {
	yield [
		put({
			type: statusTypes.FETCH_REQUEST,
			fetch
		})
	];

	const res = yield call(api, {
		method: 'GET',
		route: '/tsg/v1/posts',
		data
	});

	yield [
		put({
			type: metaTypes.META_SET_POSTS,
			payload: res.data.meta
		}),
		put({
			type: postTypes.POSTS_SET,
			payload: res.data.data
		}),
		put({
			type: statusTypes.FETCH_SUCCESS,
			fetch
		})
	];
}
