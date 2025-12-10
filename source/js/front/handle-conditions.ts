export default (($) => {
	function HandleConditions() {
		this.handleRequired();
		this.handleEvents();
	}

	HandleConditions.prototype.handleRequired = () => {
		const target = $('[class*="mod-form"]');
		$('[conditional-target]:hidden', target).find('[required]').prop('required', false).attr('hidden-required', true);
		$('[conditional-target]:visible', target).find('[hidden-required]').prop('required', true);
	};

	HandleConditions.prototype.handleEvents = function () {
		$('input[conditional]').change(
			function (e) {
				const target = $(e.target).parents('[class*="mod-form"]');
				var conditional = $(e.target).attr('conditional');
				if (typeof conditional !== 'undefined' && conditional.length > 0) {
					conditional = conditional.replaceAll("'", '"'); //HTML attribute breaks when using double quotes, therefore single quotes are used
					var conditionObj = JSON.parse(conditional);

					target.find('div[conditional-target^=\'{"label":"' + conditionObj.label + '",\']').hide();
					target.find("div[conditional-target='" + conditional + "']").show();
				}

				this.handleRequired();
			}.bind(this),
		);
	};

	return new HandleConditions();
})(jQuery);
