import {combineReducers} from 'redux';
import venues from './venues';
import meta from './meta';
import status from './status';
import pages from './pages';
import vendors from './vendors';
import categories from './categories';
import djs from './djs';
import reviews from './reviews';
import posts from './posts';

const Ducks = combineReducers({
	venues,
	meta,
	status,
	pages,
	vendors,
	categories,
	djs,
	reviews,
	posts
});

export default Ducks;
