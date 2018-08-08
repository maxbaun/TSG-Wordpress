const $ = window.jQuery;

function triggetNetlifyDeploy(hook) {
	return e => {
		e.preventDefault();

		if (hook === '' || !hook) {
			return;
		}

		// eslint-disable-next-line no-alert
		if (window.confirm('Are you sure you are ready to deploy?')) {
			$.post(hook);
		}
	};
}

document.addEventListener('DOMContentLoaded', () => {
	const btnStaging = document.querySelector('.netlifyDeployStaging');
	const btnProduction = document.querySelector('.netlifyDeployProduction');

	// Register click handlers
	btnStaging.addEventListener('click', triggetNetlifyDeploy(WordpressGlobalConstants.NetlifyStagingHook));
	btnProduction.addEventListener('click', triggetNetlifyDeploy(WordpressGlobalConstants.NetlifyProductionHook));

	// Make them look clickable
	btnProduction.style.cursor = 'pointer';
	btnStaging.style.cursor = 'pointer';
});
