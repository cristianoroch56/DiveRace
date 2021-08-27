// Updated RCM Builder Logic ---------------------------------------------------

var RCMHelper = {

  optionsGroups: {
    arrangements: {
      unselectable: false,
      allowMultiSelect: false,
      default: "bib--default",
      options: [
        "bib--default",
        "bib--split"
      ]
    },
    info: {
      unselectable: true,
      options: [
        'bib--about'
      ]
    },
    layouts: {
      unselectable: false,
      allowMultiSelect: false,
      default: "bib--row-3",
      options: [
        "bib--box-3",
        "bib--box-5",
        "bib--box-6",
        "bib--col-2",
        "bib--col-3",
        "bib--col-4",
        "bib--col-5",
        "bib--col-6",
        "bib--grd-4",
        "bib--grd-6",
        "bib--row-1",
        "bib--row-2",
        "bib--row-3",
        "bib--row-4",
        "bib--txt-1",
        "bib--txt-3",
        "bib--txt-6"
      ]
    },
    fonts: {
      unselectable: false,
      allowMultiSelect: false,
      field: "headline-font",
      default: "bib--font-arial",
      options: [
        "bib--font-courier",
        "bib--font-arial",
        "bib--font-arialblack",
        "bib--font-comic",
        "bib--font-tahoma",
        "bib--font-trebuchet",
        "bib--font-verdana",
        "bib--font-georgia",
        "bib--font-palatino",
        "bib--font-times"
      ]
    },
    fontSizes: {
      unselectable: false,
      allowMultiSelect: false,
      field: "headline-size",
      default: "bib--size-18",
      options: [
        "bib--size-14",
        "bib--size-16",
        "bib--size-18",
        "bib--size-20",
        "bib--size-22"
      ]
    },
    text: {
      unselectable: true,
      options: [
        "bib--invert"
      ]
    },
    displays: {
      unselectable: true,
      allowMultiSelect: true,
      default: "bib--hover",
      options: [
        "bib--image-only",
        "bib--title-only",
        "bib--hover"
      ]
    },
    ratios: {
      unselectable: false,
      allowMultiSelect: false,
      default: "bib--4by3",
      options: [
        "bib--wide",
        "bib--4by3",
        "bib--square",
        "bib--tall"
      ]
    },
    properties: {
      unselectable: true,
      allowMultiSelect: true,
      default: "bib--recency-show",
      options: [
        "bib--author-show",
        "bib--recency-show",
        "bib--site-show"
      ]
    },
    hoverEffects: {
      unselectable: true,
      allowMultiSelect: false,
      options: [
        "bib--shine",
        "bib--spectrum"
      ]
    },
    imageAlignments: {
      unselectable: true,
      allowMultiSelect: false,
      field: "vertical-alignment",
      default: "bib--image-middle",
      options: [
        "bib--image-top",
        "bib--image-middle",
        "bib--image-bottom"
      ]
    }
  },

  conflictingOptionGroups: [
    // --- [[base-option-n, ..., ...], [conflicting-option-n, ..., ...], [allow-option-n, ..., ...]]
    //Display Buttons
    [["bib--image-only", "bib--title-only", "bib--hover"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]],
    [["bib--image-only", "bib--title-only"], ["bib--split"]],
    [["bib--title-only"], ["bib--default"], ["bib--author-show", "bib--recency-show", "bib--site-show"]],
    //Arrangement Buttons
    [["bib--default", "bib--split"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]],
    //Image Buttons
    [["bib--shine", "bib--spectrum"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]],
    //Image Vertical Alignment
    [["vertical-alignment"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]],
    //Ratio buttons
    [["bib--wide", "bib--4by3", "bib--square", "bib--tall"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]],
    //Text buttons
    [["bib--invert"], ["bib--default"], ["bib--txt-1", "bib--txt-3", "bib--txt-6"]]
  ],

  moduleShowcaseButtons: [],

  moduleShowcaseControlSelect: [],

  moduleShowcaseStage: undefined,

  getOptionsGroup: function(option) {
    var groupKey = Object.keys(RCMHelper.optionsGroups).filter(function(key) {
      return RCMHelper.optionsGroups[key].options.includes(option);
    })[0];
    return RCMHelper.optionsGroups[groupKey] || null;
  },

  removeElementFromArray: function(arr, elem) {
    return arr.filter(function(val) {
      return val !== elem;
    });
  },

  removeOptions: function(classes, options) {
    classes = classes.filter(function(val) {
      return !options.includes(val);
    });
    return classes;
  },

  optionsNotFoundInClasses: function(classes, options) {
    // Check if the current classes contains any conflicting class
    var index = classes.findIndex(function(val) {
      return options.includes(val);
    });
    return index === -1;
  },

  isSelected: function(classes, option) {
    var classList = classes.split(" ");

    if(classList.includes(option)) {
      return true;
    }

    // If not selected, check if it is the default value of a group
    var group = RCMHelper.getOptionsGroup(option);
    if(group && group.default === option) {
      var selectedGroupOptions = classList.filter(function(option) {
        return group.options.includes(option);
      });
      return selectedGroupOptions.length === 0;
    }

    return false;
  },

  calculateSelection: function(classes, option, enabled) {
    if(!enabled)
      return classes;

    // Get all the option groups for this option
    var group = RCMHelper.getOptionsGroup(option);

    // If unselecting option
    if(classes.includes(option)) {
      // Cannot unselect when part of a unselectable group
      if(!group || group.unselectable === true) {
        classes = RCMHelper.removeElementFromArray(classes, option);
      }
    }
    // Else select the option
    else {
      // Remove all other options within the same option group if it doesn't allow multiple selects
      if(group && !group.allowMultiSelect) {
        classes = classes.filter(function(option) {
          return !group.options.includes(option);
        });
      }
      // Then add the option to the classes
      classes.push(option);
    }
    // Find missing options
    var missingClasses = Object.keys(RCMHelper.optionsGroups).map(function(key){
                                  var options = RCMHelper.optionsGroups[key].options;
                                  var default_field = RCMHelper.optionsGroups[key].default;
                                  var intersect = options.filter(value => classes.includes(value));
                                  var unselectable = RCMHelper.optionsGroups[key].unselectable;
                                  if(unselectable == true) {
                                    return undefined;
                                  } else {
                                    return (intersect.length > 0 && default_field) ? undefined : default_field;
                                  }
                                })
                               .filter(function (value) {
                                  return value != undefined;
                               });

    // Then add the missing options to the classes
    missingClasses.forEach(function(option) {
      classes.push(option);
    });

    // Filter out unknown classes
    classes = RCMHelper.filterUnknownClasses(classes);

    // Return result
    return classes;
  },

  filterUnknownClasses: function(classes) {
    var filtered_classes = Object.keys(RCMHelper.optionsGroups)
                                 .map(function(key) {
                                    var classes = RCMHelper.optionsGroups[key].options;
                                    return classes;
                                 })
                                 .flat()
                                 .filter(function(class_name) {
                                    return (classes.includes(class_name));
                                 })
                                 .concat('bib__module');
    return filtered_classes;
  },

  getRCMElement: function() {
    return document.getElementsByClassName('bib__module')[0];
  },

  getRCMClasses: function() {
    var classes = RCMHelper.getRCMElement().className;
    classes = classes.split(" ");
    return classes
  },

  removeClassFromRCM: function(class_name) {
    return RCMHelper.getRCMElement().classList.remove(class_name);
  },

  getFieldByOptionId: function(option_id) {
    var field = Object.keys(RCMHelper.optionsGroups)
                      .map(function(group_id) {
                        var group = RCMHelper.optionsGroups[group_id];
                        var options = group["options"];
                        var field = group["field"];

                        if (field != undefined) {
                          if (options.includes(option_id)) {
                            return field;
                          }
                        }
                      })
                      .filter(function (value) {
                        return value != undefined;
                      })
                      .pop();
    return field;
  },

  getChildrenOfOption: function(option_id) {
    var children = Object.keys(RCMHelper.optionsGroups)
                         .filter(function(key) {
                           return option_id == RCMHelper.optionsGroups[key]['field'];
                         })
                         .map(function(group) {
                           return RCMHelper.optionsGroups[group]['options'];
                         })
                         .flat();
      return children;
  },

  getOptionsToDisable: function(classes) {
    var classes = classes;
    var disable_options = RCMHelper.conflictingOptionGroups.map(function(option_group) {
      return classes.map(function(option_id) {
                      var base_classes = option_group[0];
                      var conflicting_classes = option_group[1];
                      var allowed_classes = option_group[2];
                      var is_option_part_of_conflicting_classes = conflicting_classes.includes(option_id);
                      var allowed_class_intersect = (allowed_classes) ? allowed_classes.filter(value => classes.includes(value)) : [];
                      if (is_option_part_of_conflicting_classes == true && !allowed_class_intersect.length) {
                        return base_classes;
                      }
                    })
                    .filter(function (value) {
                      return value != undefined;
                    })
                    .flat();
    });
    var filtered_disable_options = disable_options
          .filter(function(item, pos) { return disable_options.indexOf(item) == pos; })
          .flat();
    return filtered_disable_options;
  },

  removeOptionsOnModule: function(options) {
    options.forEach(function(id) {
      RCMHelper.removeClassFromRCM(id);
    });
  },

  disableOptionsOnBuilder: function(options) {
    options.forEach(function(id) {
      var field_id = RCMHelper.getFieldByOptionId(id);
      var element_id = (field_id == undefined) ? id : field_id;
      var element = document.getElementById(element_id);
      var element_type = element.tagName.toLowerCase();
      if (element_type == 'select') {
        element.disabled = true;
        element.removeEventListener('change', RCMHelper.handleSelection);
      } else {
        element.classList.add('inactive');
        element.classList.remove('active');
        element.removeEventListener('click', RCMHelper.handleSelection, false);
      }
    });
  },

  setOptionsOnModule: function(classes) {
    var rcm = RCMHelper.getRCMElement();
    rcm.className = classes.join(" ");
  },

  resetOptionsOnBuilder: function() {
    var moduleShowcaseButtons = RCMHelper.moduleShowcaseButtons;
    var moduleShowcaseControlSelect = RCMHelper.moduleShowcaseControlSelect;
    moduleShowcaseButtons.forEach(function(element){
      element.classList.remove('inactive');
      element.classList.remove('active');
      element.addEventListener('click', RCMHelper.handleSelection);
    });
    moduleShowcaseControlSelect.forEach(function(element){
      element.disabled = false;
      element.addEventListener('change', RCMHelper.handleSelection);
    });
  },

  selectOptionsOnBuilder: function (classes) {
    //Select buttons based on RCM classes
    var moduleShowcaseButtons = RCMHelper.moduleShowcaseButtons;
    moduleShowcaseButtons.forEach(function(element){
      var element_id = element.id;
      var element_should_be_enabled = classes.includes(element_id);
      if (element_should_be_enabled) {
        element.classList.remove('inactive');
        element.classList.add('active');
      } else {
        element.classList.remove('active');
      }
    });

    //Select select options based on RCM classes
    var moduleShowcaseControlSelect = RCMHelper.moduleShowcaseControlSelect;
    moduleShowcaseControlSelect.forEach(function(element) {
      var option_id = element.id;
      var option_children = RCMHelper.getChildrenOfOption(option_id);
      var options_to_enable = option_children.filter(function(child) {
        return classes.includes(child);
      });
      if (options_to_enable.length > 0) {
        var select_field = document.getElementById(option_id);
        var select_options = Array.prototype.slice.call(element.options);
        var select_options_values = select_options
          .map(function (option) {
            return [option.index, option.value]
          });
        select_field.disabled = false;
        select_options_values.forEach(function (option) {
          var index = option[0];
          var id = option[1];
          var element_should_be_enabled = classes.includes(id);
          if (element_should_be_enabled) {
            select_field.selectedIndex = index;
          }
        });
      }
    });
  },

  getDefaultOptionsToEnable: function(default_classes = []) {
    var classes = [];
    if (default_classes.length == 0) {
      classes = Object.keys(RCMHelper.optionsGroups)
                      .map(function(group) { return RCMHelper.optionsGroups[group].default; })
                      .filter(function (value) { return value != null; });
    } else {
      classes = default_classes;
    }

    return classes;
  },

  invertStageColourIfRequired: function() {
    var classes = RCMHelper.getRCMClasses();
    var should_invert_stage = classes.includes('bib--invert');
    if (should_invert_stage) {
      RCMHelper.moduleShowcaseStage.classList.add('invert-stage');
    } else {
      RCMHelper.moduleShowcaseStage.classList.remove('invert-stage');
    }
  },

  getValidatedClasses: function(classes) {
    if (!classes.includes('bib__module')) {
      classes.push('bib__module');
    }
    return classes;
  },

  handleSelection: function () {
    var option              = ((this.value == undefined) ? this.id : this.value);
    var enabled             = (this.classList.contains("inactive") == true) ? false : true;
    var rcm_classes         = RCMHelper.getRCMClasses();
    var new_classes         = RCMHelper.calculateSelection(rcm_classes, option, enabled);
    var options_to_disable  = RCMHelper.getOptionsToDisable(new_classes);
    RCMHelper.setOptionsOnModule(new_classes);
    RCMHelper.resetOptionsOnBuilder();
    RCMHelper.selectOptionsOnBuilder(new_classes);
    RCMHelper.removeOptionsOnModule(options_to_disable);
    RCMHelper.disableOptionsOnBuilder(options_to_disable);
    RCMHelper.invertStageColourIfRequired();
  },

  initBuilderControlListeners: function() {
    // Bind buttons to RCMHelper.handleSelection
    RCMHelper.moduleShowcaseButtons.forEach(function(element) {
      element.addEventListener('click', RCMHelper.handleSelection, false);
    });
    // Bind selectors to RCMHelper.handleSelection
    RCMHelper.moduleShowcaseControlSelect.forEach(function(element) {
      element.addEventListener('change', RCMHelper.handleSelection, false);
    });
  },

  initModuleOptions: function(option_to_enable, options_to_disable) {
    RCMHelper.setOptionsOnModule(option_to_enable);
    RCMHelper.removeOptionsOnModule(options_to_disable);
  },

  initBuilderOptions: function(option_to_enable, options_to_disable) {
    RCMHelper.selectOptionsOnBuilder(option_to_enable);
    RCMHelper.disableOptionsOnBuilder(options_to_disable);
  },

  initModule: function(parent_id, default_classes = [], option_to_enable = [], options_to_disable = []) {

    // Hard-coded contentItemId for demo purposes
  	var parentNodeId = parent_id;
  	var recommendationKey = "890c4d81-fcb4-47d4-ace2-284ae181fc77";
  	var contentItemId = "84a5e590-588d-43dd-8cb5-da37b0b45eda";
    var recommendationType = "syndicated";
    var classes = default_classes.join(" ");
  	var callbacks = {
      onRecommendationsRendered: function(event) {
        RCMHelper.initModuleOptions(option_to_enable, options_to_disable);
        RCMHelper.resetOptionsOnBuilder();
        RCMHelper.initBuilderOptions(option_to_enable, options_to_disable);
        RCMHelper.disableOptionsOnBuilder(options_to_disable);
        RCMHelper.invertStageColourIfRequired();
      },
  		onRecommendationClick: function(activityData, event) {
  			event.preventDefault();
  			return false;
  		}
  	};

  	Bibblio.initRelatedContent({
  		targetElementId: parentNodeId,
  		recommendationKey: recommendationKey,
      recommendationType: recommendationType,
  		contentItemId: contentItemId,
      showRelatedBy: false,
  		subtitleField: 'description',
      hidden: true,
      styleClasses: classes,
  	}, callbacks);
  },

  init: function (default_classes = [], parent_id = "module-showcase-stage")
  {
    RCMHelper.moduleShowcaseStage = document.getElementsByClassName(parent_id)[0];
    RCMHelper.moduleShowcaseButtons = Array.prototype.slice.call(document.getElementsByClassName("module-showcase-button"));
    RCMHelper.moduleShowcaseControlSelect = Array.prototype.slice.call(document.getElementsByClassName("module-showcase-control-select"));

    var default_classes = RCMHelper.getValidatedClasses(default_classes);
    var option_to_enable = RCMHelper.getDefaultOptionsToEnable(default_classes);
    var options_to_disable  = RCMHelper.getOptionsToDisable(option_to_enable);

    RCMHelper.initModule(parent_id, default_classes, option_to_enable, options_to_disable);
  }
}

// -----------------------------------------------------------------------------

var getDefaultModuleClasses = function() {
	return 'bib__module bib--row-3 bib--default bib--hover bib--white-label bib--font-arial bib--size-18 bib--recency-show';
};

var getCurrentModuleSettings = function() {

	var classes =	'';

	// the RCM .js file might not have loaded and created the module yet
	if (jQuery('.bib__module') && jQuery('.bib__module').attr('class')) {
		// sort classes alphabetically for comparison
		classes = jQuery('.bib__module').attr('class').trim().split(' ').sort().join(' ').trim();
	}

	return {
		name: jQuery('#modules-name').val(),
		classes: classes,
		querystringParams: jQuery('.querystring-parameter input').map(function() { return jQuery(this).val(); }).get().join('|')
	};
}

var moduleHasChanged = function() {
	if (moduleBeforeChanges) {
		var module_settings_were = JSON.stringify(moduleBeforeChanges);
		var module_settings_are  = JSON.stringify(getCurrentModuleSettings())
		return (module_settings_are !== module_settings_were) ? true : false;
	}
};

var ignoreModuleChanges = function() {
	moduleBeforeChanges = getCurrentModuleSettings();
};

var notifyUnsavedModuleChanges = function() {
	if (moduleHasChanged()) {
		if (confirm( 'Do you want to leave this page?\nChanges you made may not be saved.' )) {
			ignoreModuleChanges();
			removeUnsavedModules();
			return true;
		} else {
			return false;
		}
	}
	return true;
};

// Admin navigation tabs
var bibblioAdminNav = function() {
	var allTabs = jQuery( '.tabs' ).hide();
	jQuery( '#tab1' ).show();

	jQuery( '.tab_admin .tab_item' ).click(
		function() {
			var $this = jQuery( this );

			// check for module changes before changing tabs (and don't do anything if the user's clicking on the already active tab)
			if ( ! $this.hasClass( 'tab_item_active' ) && notifyUnsavedModuleChanges()) {
				allTabs.hide();
				removeUnsavedModules();

				jQuery( '.tab_item' ).removeClass( 'tab_item_active' );
				var currentTabs = '#' + $this.data( 'type' );
				jQuery( currentTabs ).show();
				$this.addClass( 'tab_item_active' );
			}
		}
	);
};

var clickTab = function(tabNum) {
	var tabs = jQuery( '.tab_admin .tab_item' );
	if (tabs) {
		tabs[tabNum].click();
	}
};

// Support form AJAX submission
var sendSupportEmail = function(e) {
	e.preventDefault();
	var nonce = jQuery( '#bibblio_support_nonce' ).val();
	var name = jQuery( '#support-name' )
	var email = jQuery( '#support-email' )
	var company = jQuery( '#support-company' )
	var content = jQuery( '#support-message' )
	var successMsg = jQuery( '.successMessage' );

	var data = {
		action: 'send_user_feedback',
		bibblio_support_nonce: nonce,
		full_name: name.val(),
		email: email.val(),
		company: company.val(),
		message: content.val()
	};

	jQuery.ajax(
		{type: "POST", url: ajaxurl, data: data, success: function() {
			successMsg.show( 400 );
			name.val( '' );
			email.val( '' );
			company.val( '' );
			content.val( '' );
			setTimeout( function() { successMsg.hide( 500 ); }, 3000 );
		}}
	);
};

// START of methods for updating the Overview page's values and graphs
var updateRecommendationsGraph = function(months) {
	var mostRecs = 0;
	var graphMonths = jQuery( '.analytics-container' ).find( '.barchart-column' );

	// find the highest number of recs, for calculating graph heights (as a %)
	for (var i = 0; i < months.length; i++) {
		var recs = months[i].total;
		if (recs > mostRecs) {
			mostRecs = recs;
		}
	}

	// update each month's values
	for (var j = 0; j < months.length; j++) {
		var month = months[j];
		var barHeight = (month.total / mostRecs * 100);
		if (month.total > 0) {
			jQuery( graphMonths[j] ).find( '.barchart-bar-value' ).text( month.total );
			jQuery( graphMonths[j] ).find( '.barchart-bar-value' ).css( 'bottom', barHeight + '%' );
		}
		jQuery( graphMonths[j] ).find( '.barchart-bar' ).css( 'height', barHeight + '%' );
		jQuery( graphMonths[j] ).find( '.barchart-bar-label' ).text( month.label );
	}
};

var updateOverviewStorage = function() {
	jQuery( '#storage-refresh-notice' ).fadeIn( 250 );

	// storage thermometer
	jQuery.post(
		ajaxurl, {'action': 'bibblio_storage_overview'}, function(response) {
			// all error handling and clearing is done by updateOverviewRecommendations()
			if ((response.length > 9) && (response.indexOf('Error ') == -1)) {
				jQuery('.tab_overview_storage').html(response);

				if ( storagePercent >= 100 ) {
					jQuery('#content-limit-reached-warning').slideUp( 500 );
					jQuery('#content-limit-reached-error').slideDown( 500 );
				} else if ( storagePercent >= 85 ) {
					jQuery('#content-limit-reached-warning').slideDown( 500 );
					jQuery('#content-limit-reached-error').slideUp( 500 );
				} else {
					jQuery('#content-limit-reached-warning').slideUp( 500 );
					jQuery('#content-limit-reached-error').slideUp( 500 );
				}
			}
		}
	)
	.fail(
		function(xhr) {
			console.error( 'Error making AJAX request to update storage graph!' );
		}
	)
	.always(
		function() {
			jQuery( '#storage-refresh-notice' ).fadeOut( 250 );
		}
	);
};

var updateOverviewRecommendations = function() {
	jQuery( '#recs-refresh-notice' ).fadeIn( 250 );

	// recommendations bar graph
	jQuery.post(
		ajaxurl, {'action': 'bibblio_recs_overview'}, function(response) {
			hideOverviewOffline();

			if ((response.length > 9) && (response.indexOf('Error ') == -1)) {
				hideOverviewAuthError();
				jQuery('.tab_overview_rec').html(response);

				if ( recsPercent >= 100 ) {
					jQuery('#recommendation-limit-reached-warning').slideUp( 500 );
					jQuery('#recommendation-limit-reached-error').slideDown( 500 );
				} else if ( recsPercent >= 85 ) {
					jQuery('#recommendation-limit-reached-warning').slideDown( 500 );
					jQuery('#recommendation-limit-reached-error').slideUp( 500 );
				} else {
					jQuery('#recommendation-limit-reached-warning').slideUp( 500 );
					jQuery('#recommendation-limit-reached-error').slideUp( 500 );
				}
			} else {
				// if there is an auth error
				if (response.indexOf('Error 403') == 0) {
					showOverviewAuthError();
				}
			}
		}
	)
	.fail(
		function(xhr) {
			showOverviewError( xhr );
			console.error( 'Error making AJAX request to update recommendations graph!' );
		}
	)
	.always(
		function() {
			jQuery( '#recs-refresh-notice' ).fadeOut( 250 );
		}
	);
};

var showOverviewError = function(xhr) {
	if (xhr && ((xhr === 'offline') || ! xhr.status)) {
		// client-side error (no status code)
		jQuery( '#overview-update-offline-warning' ).slideDown( 500 );
	}
};

var hideOverviewOffline = function(xhr) {
	jQuery( '#overview-update-offline-warning' ).slideUp( 500 );
};

var showOverviewAuthError = function(xhr) {
	jQuery( '#overview-update-auth-error' ).slideDown( 500 );
};

var hideOverviewAuthError = function(xhr) {
	jQuery( '#overview-update-auth-error' ).slideUp( 500 );
};

var updateOverviewPage = function() {
	updateOverviewStorage();
	updateOverviewRecommendations();
};
// END of methods for updating the Overview page's values and graphs;
// helper method for translating module "bib--" class into layout class
var getLayoutThumbnail = function(classes) {
	var layout = '';
	classes = (classes) ? classes : '';
	classes.split( ' ' ).forEach(
		function(eachClass) {
			eachClass = eachClass.replace( 'bib--', '' );
			if (jQuery.inArray( eachClass, layouts ) > -1) {
				layout = 'module-showcase-layout-' + eachClass;
			}
		}
	);
	return layout;
};

var appendModule = function(e) {
	e.preventDefault();

	var name = jQuery('#append-module-select').find(":selected").val();
	var moduleHeader = jQuery('#module_name').val();
	var nonce = jQuery( '#bibblio_append_module_nonce' ).val();

	jQuery( '#module-append' ).hide();
	jQuery( '#msg-append-saving' ).show();

	var data = {
		'action': 'bibblio_append_module',
		'bibblio_append_module_nonce': nonce,
		'module_header': moduleHeader,
		'appended_rcm': name
	};

	jQuery.post(
		ajaxurl, data, function(response) {
			try {
				if (response == 'true') {
					jQuery( window ).unbind( 'beforeunload' );

					jQuery( '#msg-append-saved' ).fadeIn( 75 );
					setTimeout( function() { jQuery( '#msg-append-saved' ).fadeOut( 600 ); }, 4000 );
				} else {
					alert( 'Error appending module, please refresh the page and try again.' );
				}
			} catch (e) {
				alert( 'Error appending module, please refresh the page and try again.' );
			}
		}
	)
	.fail(
		function() {
			console.error( 'Error making AJAX request to append module!' );
		}
	)
	.always(
		function() {
			jQuery( '#module-append' ).show();
			jQuery( '#msg-append-saving' ).hide();
		}
	);
};

var removeAppendedRcm = function(e) {
	jQuery( '#save-append' ).hide();
	jQuery( '#msg-append-saving' ).show();

	var nonce = jQuery( '#bibblio_append_module_nonce' ).val();

	var data = {
		'action': 'bibblio_append_module',
		'bibblio_append_module_nonce': nonce
	};

	jQuery.post(
		ajaxurl, data, function(response) {
			try {
				if (response == 'true') {
					jQuery( window ).unbind( 'beforeunload' );
					jQuery( '#msg-append-saved' ).fadeIn( 75 );
					setTimeout( function() { jQuery( '#msg-append-saved' ).fadeOut( 600 ); }, 3000 );
				} else {
					alert( 'Error removing appended module, please refresh the page and try again.' );
				}
			} catch (e) {
				alert( 'Error removing appended module, please refresh the page and try again.' );
			}
		}
	)
	.fail(
		function() {
			console.error( 'Error making AJAX request to remove appended module!' );
		}
	)
	.always(
		function() {
			jQuery( '#save-append' ).show();
			jQuery( '#msg-append-saving' ).hide();
		}
	);
};

// Create module admin page
var createModule = function(e) {
	e.preventDefault();

	var name = jQuery( '#modules-name' ).val();
	var recommendationType = jQuery("#recommendation-type").val();
	var selectedFont = jQuery('#headline-font').val();
	var fontSize = jQuery('#headline-size').val();

	if ( ! name) {
		alert( 'Please enter a name for your module' );

	} else {
		var nonce = jQuery( '#bibblio_create_module_nonce' ).val();
		var classes = jQuery( '.bib__module' ).attr( 'class' );
		classes = classes.replace( /bib__module /g, '' );
		classes = classes.replace( /  /g, ' ' );

		var modules = [
			{
				name: name,
				recommendationType: recommendationType,
				selectedFont: selectedFont,
				fontSize: fontSize,
				classes: classes,
				queryParams: getModuleQueryParams()
			}
		];

		var data = {
			'action': 'bibblio_create_module',
			modules: modules,
			bibblio_create_module_nonce: nonce
		};

		jQuery.post(
			ajaxurl, data, function(response) {
				if (response == 'true') {
					window.location = 'admin.php?page=bibblio_related_posts&success=true';
				} else {
					alert( 'Error saving module, please refresh the page and try again.' );
				}
			}
		)
		.fail(
			function() {
				alert( 'Error saving module, please refresh the page and try again.' );
			}
		);
	}
};

var appendFirstRcm = function() {
	jQuery( '#save-append' ).hide();
	jQuery( '#msg-append-saving' ).show();

	var moduleHeader = jQuery('#module_name').val();
	var nonce = jQuery( '#bibblio_append_first_rcm_nonce' ).val();

	var data = {
		'action': 'bibblio_append_first_module',
		'module_header': moduleHeader,
		'bibblio_append_first_rcm_nonce':  nonce
	};

	jQuery.post(
		ajaxurl, data, function(response) {
			if (response == 'true') {
				jQuery( '#msg-append-saved' ).fadeIn( 75 );
				setTimeout( function() { jQuery( '#msg-append-saved' ).fadeOut( 600 ); }, 3000 );
			} else {
				alert( 'Error appending module, please refresh the page and try again.' );
			}
		}
	)
	.fail(
		function() {
			alert( 'Error appending module, please refresh the page and try again.' );
		}
	).always(
		function() {
			jQuery( '#save-append' ).show();
			jQuery( '#msg-append-saving' ).hide();
		}
	);
};

// Update module admin page
var saveModule = function(e) {
	e.preventDefault();

	var name = jQuery( '#modules-name' ).val();
	var moduleToUpdate = jQuery( '.myModules_item.active' );
	var recommendationType = jQuery("#recommendation-type").val();
	var moduleOffset = moduleToUpdate.data( 'element' );
	var moduleNames = bibModules.map(function(module) { return module.name; });
	moduleNames[moduleOffset] = null; // ignore the selected module's name when checking for duplicate names

	// if name is blank
	if ( ! name) {
		alert( 'Please enter a name for your module' );

	// if name is duplicate
	} else if (moduleNames.indexOf(name) !== -1) {
		alert( 'Please select a unique name for your module.' );

	// else save the modules
	} else {
		jQuery( '#module-save' ).hide();
		jQuery( '#msg-module-saving' ).show();

		var nonce = jQuery( '#bibblio_update_module_nonce' ).val();
		var classes = jQuery( '.bib__module' ).attr( 'class' );
		classes = classes.replace( /bib__module /g, '' );
		classes = classes.replace( /  /g, ' ' );
		var previous_name = bibModules[moduleOffset].name
		bibModules[moduleOffset] = {
			name: name,
			classes: classes,
			recommendationType: recommendationType,
			queryParams: getModuleQueryParams()
		};

		var data = {
			'action': 'bibblio_update_modules',
			modules: bibModules,
			update_appended_rcm_if: previous_name,
			update_appended_rcm_to: name,
			bibblio_update_module_nonce: nonce
		};

		jQuery.post(
			ajaxurl, data, function(response) {
				try {
					if (response == 'true') {
						jQuery( window ).unbind( 'beforeunload' );

						// flag the module as not having any unsaved changes
						ignoreModuleChanges();

						jQuery( '#module-save' ).val( jQuery( '#module-save' ).data( 'update-label' ) );

						jQuery( '#msg-module-saved' ).fadeIn( 75 );
						setTimeout( function() { jQuery( '#msg-module-saved' ).fadeOut( 600 ); }, 3000 );

						updateModuleUiElements( moduleToUpdate, name );

					} else {
						alert( 'Error saving module, please refresh the page and try again.' );
					}
				} catch (e) {
					alert( 'Error saving module, please refresh the page and try again.' );
				}
			}
		)
		.fail(
			function() {
				console.error( 'Error making AJAX request to save modules!' );
			}
		)
		.always(
			function() {
				jQuery( '#module-save' ).show();
				jQuery( '#msg-module-saving' ).hide();
			}
		);
	}
};

var deleteModule = function(e) {
	e.preventDefault();

	if (confirm( 'Are you sure you want to delete this module?' )) {

		var moduleToDelete = jQuery( '.myModules_item.active' );
		var deleted = bibModules.splice( moduleToDelete.data( 'element' ), 1 )[0];

		var nonce = jQuery( '#bibblio_update_module_nonce' ).val();

		var data = {
			'action': 'bibblio_update_modules',
			modules: bibModules,
			bibblio_update_module_nonce: nonce,
			remove_appended_rcm_if: deleted.name
		};

		// hide demo module and unflag changes
		jQuery( '.module_actions' ).hide();
		jQuery( '.module_create' ).hide();
		jQuery( '.module_placeholder' ).show();
		ignoreModuleChanges();

		jQuery.post(
			ajaxurl, data, function(response) {
				try {
					if (response == 'true') {
						closeModuleTracking();
						redrawModuleUiElements();
					} else {
						alert( 'Error deleting module, please refresh the page and try again.' );
					}
				} catch (e) {
					alert( 'Error deleting module, please refresh the page and try again.' );
				}
			}
		)
		.fail(
			function() {
				console.error( 'Error making AJAX request to delete module!' );
			}
		);
	}
};

// Setting recency preference

var setRecency = function(e) {
	var recency_value = jQuery('#recencySlider').val();
	var nonce = jQuery( '#bibblio_recency_slider_nonce' ).val();

	var data = {
		'action': 'bibblio_set_recency',
		'bibblio_recency_slider_nonce': nonce,
		'recency_value': recency_value
	};

	jQuery.post(
		ajaxurl, data, function(response) {
			try {
				if (response == 'true') {
					jQuery( window ).unbind( 'beforeunload' );

					jQuery( '#recencySaved' ).fadeIn( 75 );
					setTimeout( function() { jQuery( '#recencySaved' ).fadeOut( 600 ); }, 3000 );
				} else {
					alert( 'Error updating account preferences, please refresh the page and try again.' );
				}
			} catch (e) {
				alert( 'Error updating account preferences, please refresh the page and try again.' );
			}
		}
	)
	.fail(
		function(response) {
			console.error( 'Error making AJAX request to update account preferences!' );
		}
	)
	.always(
		function() {
			jQuery( '#recencySaved' ).show();
		}
	);
};

// Getting recency preference
var getRecency = function() {

	var nonce = jQuery( '#bibblio_get_recency_nonce' ).val();

	var data = {
		'action': 'bibblio_get_recency',
		'bibblio_get_recency_nonce': nonce
	};

	try {
		jQuery.post(ajaxurl, data)
			.fail(function(response) {
				console.error( 'Error making AJAX request for getting account preferences!' );
			});
	} catch (e) {
		console.error( 'Error making AJAX request for getting account preferences!', e );
	}
};

// Clicking on created modules
var setModuleTileOnClicks = function() {
	jQuery( '.myModules.module-showcase-layout' ).on(
		'click', '.myModules_item', function() {
			var $this = jQuery( this );

			// check for module changes before switching/reloading module(s) (and don't do anything if the user's clicking on the already selected module)
			if ( ! $this.hasClass( 'active' ) && notifyUnsavedModuleChanges()) {

				closeModuleTracking();

				// create button clicked
				if ($this.prop( 'id' ) == 'create-module') {
					addModule();

				} else {
					// toggle the active tile
					jQuery( '.myModules_item' ).removeClass( 'active' );
					$this.addClass( 'active' );

					// apply its styling to the module
					var module = bibModules[$this.data( 'element' )];
          var default_classes = module.classes.split(" ");

          RCMHelper.init(default_classes);
          //jQuery( '#module-showcase-stage' ).prop( 'class', 'bib__module ' + module.classes );
          //toggleAllShowcaseControlButtonsBasedOnModule( ".bib__module" ); // reset the module controls

					// create the query parameter fields
					jQuery('.querystring-parameter').remove();

					var queryParams;
					try {
						queryParams = JSON.parse(module.queryParams);
					} catch (e) {
						queryParams = {};
					}

					for (var key in queryParams) {
						if (queryParams.hasOwnProperty(key)) {
							var value = queryParams[key];
							// querystringFields is defined in "module-setting-layer.php"
							var fields = jQuery(querystringFields).clone();
							jQuery(fields).find('input[name=name]').val(key);
							jQuery(fields).find('input[name=value]').val(value);
							jQuery('#querystring-parameters').append(fields)
							jQuery(fields).show();
						}
					}

					// update the "name" field
					jQuery( '#modules-name' ).val( module.name );

					// update the "recommendation type" field
					if( module.recommendationType == 'related' ) {
						jQuery( '#recommendation-type' ).val( 'related' );
					}else{
						jQuery( '#recommendation-type' ).val( 'optimised' );
					}

					var saveLabel = (module.unsaved) ? jQuery( '#module-save' ).data( 'save-label' ) : jQuery( '#module-save' ).data( 'update-label' );
					jQuery( '#module-save' ).val( saveLabel );

					ignoreModuleChanges();

					// show the module builder
					jQuery( '.module_placeholder' ).hide();
					jQuery( '.module_actions' ).show();
					jQuery( '.module_create' ).show();
				}
			}
		}
	);
};

var renderDemoContentModule = function(wizard) {
  RCMHelper.init();
};

var isWizard = false;
var moduleBeforeChanges = false;

// Related content module builder
var layouts = [
	'box-3', 'box-5', 'box-6', 'grd-4',
	'grd-6', 'row-1', 'row-2', 'row-3', 'row-4',
	'col-2', 'col-3', 'col-4', 'col-5', 'col-6',
    'txt-1', 'txt-3', 'txt-6'
];

var ratios = ['default', 'square', 'wide'];

var ratioLayoutConflicts = [
	{
		ratio: 'square',
		layouts: ['box-3', 'box-4', 'box-6']
	},
	{
		ratio: 'wide',
		layouts: ['box-3', 'box-4', 'box-6']
	}
];

var classNameStem = "bib--";
var ratioButtonSelectorStem = 'div.module-showcase-ratio-';

// The core of the showcase Javascript
var _isInList = function(list, item) {
	return (jQuery.inArray( item, list ) > -1);
};

var getCurrentModuleClasses = function(moduleNodeSelector) {
	var classes = jQuery( moduleNodeSelector ).attr( 'class' );
	return (classes) ? classes.split( " " ) : [];
};

var removeModuleClasses = function(moduleNodeSelector, classNames, classNameStem) {
	jQuery( classNames ).each(
		function(index, className) {
			jQuery( moduleNodeSelector ).removeClass( classNameStem + className );
		}
	);
};

var removeModuleClass = function(moduleNodeSelector, className, classNameStem) {
	jQuery( moduleNodeSelector ).removeClass( classNameStem + className );
};

var addModuleClass = function(moduleNodeSelector, className, classNameStem) {
	jQuery( moduleNodeSelector ).addClass( classNameStem + className );
};

var toggleModuleClass = function(moduleNodeSelector, className, classNameStem) {
	jQuery( moduleNodeSelector ).toggleClass( classNameStem + className );
};

var getCurrentModuleLayoutClass = function(moduleSelector) {
	// Get current module layout className
	var layoutClassName = false;
	var classes = jQuery( moduleSelector ).prop( 'class' );
	classes = (classes) ? classes : '';
	classes.split( ' ' ).forEach(
		function(eachClass) {
			eachClass = eachClass.replace( classNameStem, '' );
			if (jQuery.inArray( eachClass, layouts ) > -1) {
				layoutClassName = eachClass;
			}
		}
	);
	return layoutClassName;
};

var fixConflictingRatioClassesOnModule = function(moduleSelector, layoutClassName) {
	var currentModuleLayoutClass = getCurrentModuleLayoutClass( moduleSelector );

	// For each ratioLayourConflict - check if current layour conflicts with a given ratio
	jQuery( ratioLayoutConflicts ).each(
		function(index, conflictMap) {
			var ratio = conflictMap.ratio;
			var layoutClassNames = conflictMap.layouts;
			// If current layout conflicts with a given ratio
			if (_isInList( layoutClassNames, currentModuleLayoutClass )) {
				// Set the modules ratio to default
				addNewModuleClass( moduleSelector, ratios, 'default' );
			}
		}
	);
};

var addNewModuleClass = function(moduleSelector, classes, className) {
	removeModuleClasses( moduleSelector, classes, classNameStem );
	addModuleClass( moduleSelector, className, classNameStem );
};

var onModuleOnShowCaseButtonClick = function(moduleSelector, className) {
	var currentModuleClasses = getCurrentModuleClasses( moduleSelector );
	// Is a layout class?
	if (_isInList( layouts, className )) {
		// Exectute layout logic
		addNewModuleClass( moduleSelector, layouts, className, classNameStem );
		fixConflictingRatioClassesOnModule( moduleSelector, className );
		toggleAllShowcaseControlButtonsBasedOnModule( moduleSelector );
		// Else is a ratio class?
	} else if (_isInList( ratios, className )) {
		// Exectute ratio logic
		addNewModuleClass( moduleSelector, ratios, className, classNameStem );
		toggleAllShowcaseControlButtonsBasedOnModule( moduleSelector );
		// Else is generic
	} else {
		// Exectute generic logic
		toggleModuleClass( moduleSelector, className, classNameStem );
		toggleAllShowcaseControlButtonsBasedOnModule( moduleSelector );
	}

	if ( ! isWizard) {
		if (moduleHasChanged()) {
			showWarningWhenNavigatingAway();
		} else {
			jQuery( window ).unbind( 'beforeunload' );
		}
	}
};

var initShowcaseControlButtonListeners = function(moduleSelector) {
	var buttons = jQuery( "div.module-showcase-button" );
	jQuery( buttons ).each(
		function(index, element) {
			// Bind the toggleModule.. function to the click event of each showcase control button
			jQuery( element ).click(
				function() {
					var className = jQuery( this ).attr( 'id' );
					onModuleOnShowCaseButtonClick( moduleSelector, className );
				}
			);
		}
	);
};

var toggleAllShowcaseControlButtonsBasedOnModule = function(moduleSelector) {

};

var initShowcaseControls = function() {
	// This function polls a ul node with class bib__module, until it is visible
	// Then performs logic on the showcase controls
	// Note: If the Bower module gets an onLoad callback, this polling logic can be avoided
	var recurTimeoutMs = 333;
	var moduleStageSelector = "#module-showcase-stage";
	var moduleStageIsVisible = jQuery( moduleStageSelector ).is( ':visible' );
	if (moduleStageIsVisible) {
    //initDemoRelatedContentModule();
	} else {
		setTimeout( initShowcaseControls, recurTimeoutMs );
	}
};

var initDemoRelatedContentModule = function(options) {
	// Hard-coded contentItemId for demo purposes
  var defaultClasses = [];
	var parentNodeId = "module-showcase-stage";
  RCMHelper.init(defaultClasses, parentNodeId);
};

var updateAppendRcmDropdown = function(updateType) {
	updateType = updateType || 'redraw';

	var moduleDropdown = '<option value="">None</option>';

	for (var i = 0; i < bibModules.length; i++) {
		var module = bibModules[i];

		// check if unsaved
		var unsaved = (module.unsaved) ? ' (not saved!)' : ''
		var disabled = (module.unsaved) ? ' disabled="disabled"' : ''

		moduleDropdown += '<option value="' + module.name + '"' + disabled +'>' + module.name + unsaved + '</option>';
	}

	// reselect the dropdown option that was select
	var dropdown = jQuery( '#append-module-select' );
	var old_value = dropdown.val() || '';

	// adding or deleting a module (so "name" hasn't changed)
	if (updateType == 'redraw') {
		dropdown.html( moduleDropdown );
		dropdown.val( old_value );

		if (dropdown.val() == null) {
			dropdown.val(''); // "None" option
		}

	// updating a module ("name" might have changed)
	} else {
		var selectedOffset = 0;
		var dropdownOptions = jQuery( '#append-module-select option' );

		for (var i = 0; i < dropdownOptions.length; i++) {
			if (jQuery(dropdownOptions[i]).attr('selected') === 'selected') {
				selectedOffset = i;
				break;
			}
		}

		dropdown.html( moduleDropdown );
		jQuery(jQuery( '#append-module-select option' )[selectedOffset]).attr('selected', 'selected');
	}
}

// called when you Add or Delete a module
var redrawModuleUiElements = function() {
	if (bibModules) {
		var createModuleButton = '';
		createModuleButton = createModuleButton + '<div id="create-module" class="myModules_item" title="Create a new module">';
		createModuleButton = createModuleButton + '    <div class="myModules_item_type myModules_item_type_plus">';
		createModuleButton = createModuleButton + '        <i class="fa fa-plus fa-2x" aria-hidden="true"></i>';
		createModuleButton = createModuleButton + '    </div>';
		createModuleButton = createModuleButton + '    <div class="myModules_item_text">Create a new module</div>';
		createModuleButton = createModuleButton + '</div>';

		var moduleButtons = '';

		for (var i = 0; i < bibModules.length; i++) {
			var module = bibModules[i];
			moduleButtons = moduleButtons + '<div class="myModules_item ' + getLayoutThumbnail( module.classes ) + '" data-element="' + i + '" title="' + module.name + '">';
			moduleButtons = moduleButtons + '    <div class="myModules_item_type myModules_item_type_lead module-showcase-layout-outer">';
			moduleButtons = moduleButtons + '        <span></span>';
			moduleButtons = moduleButtons + '        <span></span>';
			moduleButtons = moduleButtons + '        <span></span>';
			moduleButtons = moduleButtons + '        <span></span>';
			moduleButtons = moduleButtons + '        <span></span>';
            moduleButtons = moduleButtons + '        <span></span>';
			moduleButtons = moduleButtons + '    </div>';
			moduleButtons = moduleButtons + '    <div class="myModules_item_text">' + module.name + '</div>';
			moduleButtons = moduleButtons + '</div>';
		}

		jQuery( '.myModules.module-showcase-layout' ).html( moduleButtons + createModuleButton );

		updateAppendRcmDropdown('redraw');
	}
};

// called when you Update a module
var updateModuleUiElements = function(moduleNavToUpdate, name) {
	// update the module nav button
	jQuery( moduleNavToUpdate ).find( '.myModules_item_text' ).text( name );
	var classes = jQuery( '.bib__module' ).attr( 'class' );
	var layout = getLayoutThumbnail( classes );
	jQuery( moduleNavToUpdate ).attr( 'class', 'myModules_item ' + layout + ' active' );

	// update the "append RCM" dropdown
	updateAppendRcmDropdown('update');
};

var addModule = function() {

	if (bibModules) {
    var default_classes = RCMHelper.getDefaultOptionsToEnable();

		bibModules.push(
			{
				name: 'NEW',
				classes: default_classes.join(" "),
				unsaved: true
			}
		);

    redrawModuleUiElements();
		showWarningWhenNavigatingAway();

    RCMHelper.init(default_classes);

		var moduleButtons = jQuery( '.myModules_item' );
		jQuery( moduleButtons[moduleButtons.length - 2] ).click();
		moduleBeforeChanges = 'new';
	}
};

var removeUnsavedModules = function() {
	// have to work backwards through the array to avoid messing with offsets while looping
	for (var i = (bibModules.length - 1); i > -1; i--) {
		var module = bibModules[i];
		if (module.unsaved) {
			bibModules.splice( i, 1 );
			jQuery(jQuery('.myModules_item')[i]).remove();
			updateAppendRcmDropdown('redraw');
		}
	}
};

var showWarningWhenNavigatingAway = function() {
	jQuery( window ).bind(
		'beforeunload', function() {
			// most browsers will ignore this and display their own choice of message
			return 'Do you want to leave this page?\nChanges you made may not be saved.';
		}
	);
};

var getModuleQueryParams = function() {
	var queryParams = {};

	var queryKeys = jQuery('#querystring-parameters input[name=name]');
	var queryVals = jQuery('#querystring-parameters input[name=value]');

	for (var i = 0; i < queryKeys.length; i++) {
		var key = jQuery(queryKeys[i]).val().trim();
		if (key && (key != '')) {
			var value = jQuery(queryVals[i]).val().trim();
			queryParams[key] = value;
		}
	}

	return JSON.stringify(queryParams);
}
