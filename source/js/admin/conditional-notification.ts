(($) => {
	var selectionsArray = [];

	function Notification() {
		$(
			function () {
				//Init from saved data
				this.init();

				//Get some fresh data (from form)
				this.dataGathering();

				//Update selects
				this.updateMainSelect();
				this.updateSubSelect();

				//Reload datas where an update might have happend
				$(document).on(
					"click",
					'.post-type-mod-form input[type="checkbox"], .post-type-mod-form .acf-tab-button',
					function (event) {
						//Get some fresh data (init)
						this.dataGathering();

						//Update selects
						this.updateMainSelect();
						this.updateSubSelect();
					}.bind(this),
				);

				//Update subselect when main select is changed
				$("[data-name='form_conditional_field'] .acf-input select").change(
					function (event) {
						this.updateSubSelect();
					}.bind(this),
				);
			}.bind(this),
		);
	}

	Notification.prototype.init = () => {
		if (
			typeof notificationConditions != "undefined" &&
			notificationConditions !== null
		) {
			notificationConditions = JSON.parse(notificationConditions);
			["conditional_field", "conditional_field_equals"].forEach((fieldType) => {
				$("[data-name='notify'] .acf-row:not(.acf-clone)").each(
					(row_index, row) => {
						if (notificationConditions[row_index] !== null) {
							var currentSelect = $(
								"[data-name='form_" + fieldType + "'] .acf-input select",
								row,
							);
							currentSelect.empty();
							currentSelect.append(
								$("<option></option>")
									.attr("value", notificationConditions[row_index][fieldType])
									.text(notificationConditions[row_index][fieldType])
									.attr("selected", "selected"),
							);
						}
					},
				);
			});
		}
	};

	Notification.prototype.updateMainSelect = function () {
		$("[data-name='notify'] .acf-row:not(.acf-clone)").each(
			((row_index, row) => {
				//Get conditional field
				var conditionalField = $(
					"[data-name='form_conditional_field'] .acf-input select",
					row,
				);

				//Get previous value
				var previousValue = $(conditionalField).val();

				//Empty field(s)
				$(conditionalField).empty();

				//Add selectable values
				$.each(selectionsArray, (value_index, value) => {
					if (previousValue == value_index) {
						$(conditionalField).append(
							$("<option></option>")
								.attr("value", value_index)
								.text(value_index)
								.attr("selected", "selected"),
						);
					} else {
						$(conditionalField).append(
							$("<option></option>")
								.attr("value", value_index)
								.text(value_index),
						);
					}
				});
			}).bind(this),
		);
	};

	Notification.prototype.updateSubSelect = function () {
		$("[data-name='notify'] .acf-row:not(.acf-clone)").each(
			((row_index, row) => {
				//Get conditional field
				var conditionalFieldEquals = $(
					"[data-name='form_conditional_field_equals'] .acf-input select",
					row,
				);
				var conditionalField = $(
					"[data-name='form_conditional_field'] .acf-input select",
					row,
				);

				//Get previous value
				var previousValueEquals = $(conditionalFieldEquals).val();
				var previousValue = $(conditionalField).val();

				//Empty field(s)
				$(conditionalFieldEquals).empty();

				//Add selectable values
				$.each(selectionsArray, (value_index, value) => {
					if (conditionalField.val() == value_index) {
						//Fill avabile selects
						$.each(selectionsArray[value_index], (i, v) => {
							if (previousValueEquals == v) {
								$(conditionalFieldEquals).append(
									$("<option></option>")
										.attr("value", v)
										.text(v)
										.attr("selected", "selected"),
								);
							} else {
								$(conditionalFieldEquals).append(
									$("<option></option>").attr("value", v).text(v),
								);
							}
						});
					}
				});
			}).bind(this),
		);
	};

	Notification.prototype.dataGathering = () => {
		//Reset
		selectionsArray = {};

		$("[data-name='form_fields'] .layout").each(
			function (layout_index, layout) {
				//Get current layout
				var currentLayout = $(layout).attr("data-layout");
				var currentNameKey = $(
					".acf-fields  [data-name='label'] .acf-input .acf-input-wrap input",
					layout,
				).val();

				//Check i valid
				if (["select", "radio"].indexOf(currentLayout) != -1) {
					var isRequired = $(
						"[data-name='required'] .acf-input label input",
						layout,
					).prop("checked"); // Check if field is required
					var hasCondition = $(
						"[data-name='conditional_logic'] .acf-input label input",
						layout,
					).prop("checked"); // Check is field is conditional

					if (isRequired && !hasCondition) {
						//Define where to store values
						selectionsArray[currentNameKey] = [];

						//Get & filter data vars
						var dataField = $(
							"[data-name='values'] tbody [data-name='value'] .acf-input .acf-input-wrap input[type='text']",
							layout,
						);

						//Check that field isen't clone
						$(dataField)
							.filter((item_key, item_value) => {
								// Not a clone field?
								if ($(item_value).attr("name").includes("acfclonefield")) {
									return false;
								}
								return true;
							})
							.each(
								((data_index, data_value) => {
									if ($(data_value).val() && currentNameKey) {
										selectionsArray[currentNameKey].push($(data_value).val());
									}
								}).bind(this),
							);
					}
				}
			},
		);
	};

	return new Notification();
})(jQuery);
