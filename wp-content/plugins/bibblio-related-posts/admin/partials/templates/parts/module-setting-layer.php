<?php
/**
 * Used for managing aspects of the Widget (eg: recommendation key)
 *
 * @package    Bibblio_Related_Posts
 * @author     Bibblio <wordpress@bibblio.org>
 * @license    https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link       http://www.bibblio.org
 */

?>
<div id="module-settings-container" style="display: none">
	<div id="recommendation-type-container" class="module-settings">
		<h4>Recommendation Type</h4>
		<p>This module will show suggestions that are optimized for:</p>
		<p>
			<select class="module-showcase-control-select" name="recommendation-type" id="recommendation-type">
				<option value="optimised" selected="selected">Improved page views</option>
				<option value="related">Relevance only</option>
			</select>
		</p>
		<div class="information-box" style="width: 600px;">
			<div><strong>Improved page views</strong><br>
			Relevant suggestions that are also influenced by their popularity.</div><br>
			<div><strong>Relevance only</strong><br>
			Relevant suggestions based purely on their content.</div>
		</div>
	</div>
	<div id="querystring-parameters-container" class="module-settings">
		<h4>Manage Tracking</h4>
		<p>Monitor the performance of your related post links by adding tracking codes to them.</p>
		<div id="querystring-parameters">
			<div class="querystring-parameter">
				<label>Name</label><input type="text" name="name" class="query-param-name"><label>Value</label><input type="text" name="value" class="query-param-value">
				<a href="javascript:;" onclick="removeQuerystringParameter(this)" class="querystring-parameter-delete">&times;</a>
			</div>
		</div>
		<div><a href="javascript:;" onclick="addQuerystringParameter()" class="module-setting-link">Add new tracking parameter</a></div>
	</div>
</div>
<div class="module-showcase">
    <div class="module-showcase-controls">
        <div class="module-showcase-layout">
            <div class="module-showcase-radiobuttons">
                <div class="module-showcase-control-title">Module Layout</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-layout-row">
                        <div class="module-showcase-control-subtitle">Row</div>
                        <div class="module-showcase-button module-showcase-layout-row-1" id="bib--row-1">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-row-2" id="bib--row-2">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-row-3" id="bib--row-3">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-row-4" id="bib--row-4">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="module-showcase-layout-col">
                        <div class="module-showcase-control-subtitle">Column</div>
                        <div class="module-showcase-button module-showcase-layout-col-2" id="bib--col-2">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-col-3" id="bib--col-3">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-col-4" id="bib--col-4">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-col-5" id="bib--col-5">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-col-6" id="bib--col-6">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="module-showcase-layout-grd">
                        <div class="module-showcase-control-subtitle">Grid</div>
                        <div class="module-showcase-button module-showcase-layout-grd-4" id="bib--grd-4">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-grd-6" id="bib--grd-6">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="module-showcase-layout-box">
                        <div class="module-showcase-control-subtitle">Box</div>
                        <div class="module-showcase-button module-showcase-layout-box-3" id="bib--box-3">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-box-5" id="bib--box-5">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-box-6" id="bib--box-6">
                            <div class="module-showcase-button-icon module-showcase-layout-outer">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="module-showcase-layout-txt">
                        <div class="module-showcase-control-subtitle">Text-only</div>
                        <div class="module-showcase-button module-showcase-layout-txt-1" id="bib--txt-1">
                            <div class="module-showcase-button-icon module-showcase-layout-outer"><span><span></span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-txt-3" id="bib--txt-3">
                            <div class="module-showcase-button-icon module-showcase-layout-outer"><span><span></span><span></span><span></span></span>
                            </div>
                        </div>
                        <div class="module-showcase-button module-showcase-layout-txt-6" id="bib--txt-6">
                            <div class="module-showcase-button-icon module-showcase-layout-outer"><span><span></span><span></span><span></span><span></span><span></span><span></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="module-showcase-settings">
            <div class="module-showcase-arrangement">
                <div class="module-showcase-control-title">Arrangement</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-radiobuttons">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-arrangement-default" id="bib--default"><span class="module-showcase-button-icon"><span></span><span></span></span><span class="module-showcase-button-label">Text upon image</span></div>
                            <div class="module-showcase-button module-showcase-arrangement-split" id="bib--split"><span class="module-showcase-button-icon"><span></span><span></span></span><span class="module-showcase-button-label">Text beneath image</span></div>
                        </div>
                        <hr>
                    </div>
                    <div class="module-showcase-checkboxes">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-arrangement-about" id="bib--about" title="Reassure your audience of the authenticity of these recommendations"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">Explanatory link beneath module</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="module-showcase-ratio">
                <div class="module-showcase-control-title">Tile Ratio</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-radiobuttons">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-ratio-wide" id="bib--wide"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">Wide</span></div>
                            <div class="module-showcase-button module-showcase-ratio-4by3" id="bib--4by3"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">4:3</span></div>
                            <div class="module-showcase-button module-showcase-ratio-square" id="bib--square"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">Square</span></div>
                            <div class="module-showcase-button module-showcase-ratio-tall" id="bib--tall"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">Tall</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="module-showcase-text">
                <div class="module-showcase-control-title">Text</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-selects">
                        <div class="module-showcase-control-column">
                            <label>Headline style</label>
                            <select class="module-showcase-control-select" name="headline-font" id="headline-font">
                                <optgroup label="Monospace">
                                    <option value="bib--font-courier">Courier New</option>
                                </optgroup>
                                <optgroup label="Sans-serif">
                                    <option value="bib--font-arial">Arial</option>
                                    <option value="bib--font-arialblack">Arial Black</option>
                                    <option value="bib--font-comic">Comic Sans</option>
                                    <option value="bib--font-tahoma">Tahoma</option>
                                    <option value="bib--font-trebuchet">Trebuchet</option>
                                    <option value="bib--font-verdana">Verdana</option>
                                </optgroup>
                                <optgroup label="Serif">
                                    <option value="bib--font-georgia">Georgia</option>
                                    <option value="bib--font-palatino">Palatino Linotype</option>
                                    <option value="bib--font-times">Times New Roman</option>
                                </optgroup>
                            </select>
                            <select class="module-showcase-control-select" name="headline-size" id="headline-size">
                                <option value="bib--size-14">14px</option>
                                <option value="bib--size-16">16px</option>
                                <option value="bib--size-18">18px</option>
                                <option value="bib--size-20">20px</option>
                                <option value="bib--size-22">22px</option>
                            </select>
                        </div>
                        <hr>
                    </div>
                    <div class="module-showcase-checkboxes">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-text-invert" id="bib--invert" title="Invert the tile text to white"><span class="module-showcase-button-icon"><span></span></span><span class="module-showcase-button-label">Invert to white</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="module-showcase-image">
                <div class="module-showcase-control-title">Image</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-selects selects-horiz">
                        <div class="module-showcase-control-column">
                            <div id="image-verticle-wrapper" class="module-showcase-vertical-alignment ">
                                <label>Vertical alignment</label>
                                <select class="module-showcase-control-select" name="vertical-alignment" id="vertical-alignment">
                                    <option value="bib--image-top">Top</option>
                                    <option value="bib--image-middle">Middle</option>
                                    <option value="bib--image-bottom">Bottom</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="module-showcase-radiobuttons">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-image-shine" id="bib--shine" title="Creates a white shine effect over the right side of a tile when the mouse hovers over it"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Hover for shine effect</span></div>
                            <div class="module-showcase-button module-showcase-image-spectrum" id="bib--spectrum" title="Creates a spectrum effect over the right side of a tile when the mouse hovers over it"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Hover for spectrum effect</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="module-showcase-property">
                <div class="module-showcase-control-title">Properties</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-checkboxes">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-property-author" id="bib--author-show" title="Displays the author of a recommended content item"><span class="module-showcase-button-icon"><span></span><span></span></span><span class="module-showcase-button-label">Author</span></div>
                            <hr>
                            <div class="module-showcase-button module-showcase-property-date" id="bib--recency-show" title="Displays the date a recommended content item was first published"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Date published</span></div>
                            <hr>
                            <div class="module-showcase-button module-showcase-property-site" id="bib--site-show" title="Displays the author of a recommended content item"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Site domain</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="module-showcase-display">
                <div class="module-showcase-control-title">Display</div>
                <div class="module-showcase-control-inner">
                    <div class="module-showcase-checkboxes">
                        <div class="module-showcase-control-column">
                            <div class="module-showcase-button module-showcase-display-image" id="bib--image-only" title="Tiles show their image only and reveal the title on hover"><span class="module-showcase-button-icon"><span></span><span></span></span><span class="module-showcase-button-label">Hover for title</span></div>
                            <hr>
                            <div class="module-showcase-button module-showcase-display-title" id="bib--title-only" title="Tiles show their titles only and reveal any selected properties on hover"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Hover for properties</span></div>
                            <hr>
                            <div class="module-showcase-button module-showcase-display-hover" id="bib--hover" title="Tiles show their titles and any selected properties, and reveal a preview of the content on hover"><span class="module-showcase-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></span><span class="module-showcase-button-label">Hover for preview</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="module-showcase-stage" class="module-showcase-stage">

    </div>
</div>

	<script>

		jQuery('#headline-font').change(function(e) {
			var selectedFont = jQuery("#headline-font").val();
			var fontClass    = "bib--font-" + selectedFont;
			var bibModule = jQuery(".bib__module");
			var bibModuleClasses = bibModule.attr("class").split(' ');

			for(classes in bibModuleClasses) {
				if(bibModuleClasses[classes].startsWith("bib--font-")){
					jQuery(".bib__module").removeClass(bibModuleClasses[classes]);
				};
			}

			jQuery(".bib__module").addClass(fontClass);
		});

		jQuery('#headline-size').change(function(e) {
			var selectedFontSize = jQuery("#headline-size").val();
			var newFontSizeClass = "bib--size-" + selectedFontSize;
			var bibModule = jQuery(".bib__module");
			var bibModuleClasses = bibModule.attr("class").split(' ');

			for(classes in bibModuleClasses) {
				if(bibModuleClasses[classes].startsWith("bib--size-")){
					jQuery(".bib__module").removeClass(bibModuleClasses[classes]);
				};
			}

			jQuery(".bib__module").addClass(newFontSizeClass);
		});

		var querystringFields = jQuery(jQuery('.querystring-parameter')[0]).clone();
		jQuery(querystringFields).find('input').val('');
		jQuery(querystringFields).hide();
		jQuery('.querystring-parameter')[0].remove();

		var addQuerystringParameter = function() {
			var fields = jQuery(querystringFields).clone();
			jQuery('#querystring-parameters').append(fields)
			jQuery(fields).slideDown( 250 );
		}

		var removeQuerystringParameter = function(removeLink) {
			var row = jQuery(removeLink).closest('.querystring-parameter');
			row.slideUp( 250, function() { jQuery(this).remove(); } );
		}

		var toggleModuleTracking = function(e) {
			var slidingDown = (jQuery('#module-settings-container').hasClass("slidingDown"));

			if (slidingDown) {
				jQuery('#module-settings-container').removeClass('slidingDown');
				jQuery('#module-settings-container').stop().slideUp(250, function() {
					jQuery('#module-settings-container').css("height", "")
				});
				jQuery('#manage-tracking').text('Module Settings ▼');

			} else {
				jQuery('#module-settings-container').addClass('slidingDown');
				jQuery('#module-settings-container').stop().slideDown(250, function() {
					jQuery('#module-settings-container').css("height", "")
				});
				jQuery('#manage-tracking').text('Module Settings ▲');
			}
		}

		var closeModuleTracking = function() {
			jQuery('#module-settings-container').removeClass('slidingDown');
			jQuery('#module-settings-container').stop().slideUp(125, function() {
				jQuery('#module-settings-container').css("height", "")
			});
			jQuery('#manage-tracking').text('Module Settings ▼');
		}

	</script>
