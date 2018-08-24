import {fork} from 'redux-saga/effects';

import {watchVenues} from './venues';
import {watchPages} from './pages';
import {watchVendors} from './vendors';
import {watchReviews} from './reviews';
import {watchDjs} from './djs';
import {watchPosts} from './posts';

export default function * SVGPathSegArcAbs() {
	yield [fork(watchVenues), fork(watchPages), fork(watchVendors), fork(watchReviews), fork(watchDjs), fork(watchPosts)];
}
