/**
 * Created by hiweb on 16.04.2016.
 */

jQuery(document).ready(function () {

    var hw_theme_switcher_getPosts = function(){
        var R = {};
	    var postTypes = jQuery('#hw_theme_switcher_post_types').val();
	    var select = jQuery('#hw_theme_switcher_post_ids');
	    jQuery.ajax({
		    url: ajaxurl + '?action=hw_theme_switcher',
		    data: {postTypes: postTypes},
		    type: 'post',
		    dataType: 'json',
		    success: function(data){
			    var values = jQuery('#hw_theme_switcher_post_ids').val();
			    select.html('');
			    if(typeof values != 'object') values = [];
			    for(var group in data){
				    select.append('<optgroup label="'+group+'">');
				    for(var option in data[group]){
					    var value = data[group][option]['value'];
					    var text = data[group][option]['text'];
					    var selected = jQuery.inArray(value + '', values) != -1 ? 'selected' : '';
					    select.find('optgroup[label="'+group+'"]').append('<option value="'+value+'" '+ selected +'>'+text+'</option>');
				    }
				    select.append('</optgroup>');
			    }
			    jQuery('#hw_theme_switcher_post_ids').trigger("chosen:updated");
		    },
		    error: function(data){
			    
		    }
	    });
    };
    var tabElements = [];
    var postTypesSelect = [];
	
    jQuery('#hw_theme_switcher_post_types option').each(function(){
        tabElements.push(jQuery(this).html());
    });
	
    jQuery('#hw_theme_switcher_post_types_tabs').tabSelect({
        tabElements: tabElements,
        formElement: '#hw_theme_switcher_post_types',
        onChange: function(){
            hw_theme_switcher_getPosts();
        }
    });
	
    jQuery('#hw_theme_switcher_post_ids').chosen({width: "100%"});

});