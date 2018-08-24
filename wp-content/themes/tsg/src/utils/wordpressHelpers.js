export const replaceLinks = str => {
	if (!str) {
		return '';
	}

	return str
		.replace('http://tsg.info', '')
		.replace('https://tsg.info', '')
		.replace('http://tsgweddings.com', '')
		.replace('https://tsgweddings.com', '')
		.replace('http://tsg.d3applications.com', '')
		.replace('https://tsg.d3applications.com', '')
		.replace('http://admin.tsgweddings.com', '')
		.replace('https://admin.tsgweddings.com', '')
		.replace('http://localhost:3000', '');
};

export const isExternalLink = str => {
	const replaced = replaceLinks(str);

	if (replaced.includes('https://') || replaced.includes('http://')) {
		return true;
	}

	return false;
};

export const replaceContent = content => {
	return replaceLinks(content);
};

export const innerHtml = content => {
	content = replaceContent(content);

	return {__html: content};
};

export const sortByMenuOrder = list => {
	if (!list) {
		return;
	}

	return list.sort((a, b) => {
		if (a.node) {
			return a.node.menuOrder - b.node.menuOrder;
		}

		return a.menuOrder - b.menuOrder;
	});
};

export const bioName = name => {
	return getBioPart(name, 0);
};

export const bioPosition = name => {
	return getBioPart(name, 1);
};

function getBioPart(name, part) {
	const separator = name.includes(`8211`) ? ` &#8211; ` : ' - ';
	const parts = name.split(separator);

	return parts[part];
}

export const limitToWords = (str, numberOfWords) => {
	let result = str;
	let resultArray = result.split(' ');
	if (resultArray.length > numberOfWords) {
		resultArray = resultArray.slice(0, numberOfWords);
		result = resultArray.join(' ') + '...';
	}

	return result;
};

export const limitToCharacters = (str, numberOfCharacters) => {
	return str.substring(0, numberOfCharacters) + '...';
};
