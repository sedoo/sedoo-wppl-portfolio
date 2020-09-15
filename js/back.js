
//////
// FILL SELECT TERM FIELD BY SELECT TAXONOMY VALUE (fill field field_5f05b47483517)
//////
jQuery(document).ready(function(){    
    jQuery(document).on('click', '[data-key="field_5f05b3fd83516"] .acf-input select', function(e) { 
        jQuery('.acf-field-5f05b47483517 select').empty(); // empty cpt select

        taxo_for_terme = jQuery(this).val(); 

        jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_populate_term_depend_on_taxo', 'taxonomie': taxo_for_terme },
            success:function(result_term_list) {
                if(result_term_list.length != 0  ) {
                    jQuery('.acf-field-5f05b47483517 select').append('<option value=""> Selectionner </option>'); 
                    for (const [key, value] of Object.entries(result_term_list)) {
                        jQuery('.acf-field-5f05b47483517 select').append('<option value="'+key+'">'+value+'</option>'); 
                    }
                } else {
                    jQuery('.acf-field-5f05b47483517 select').append('<option value=""> Aucun terme </option>'); 
                }
            }
        });
    });
});

//////
// FILL TAXO FIELD BY POST TYPE VALUE (fill field field_5f05b5841a7fa)
//////
jQuery(document).ready(function(){    
    jQuery(document).on('click', '[data-key="field_5f05b51fd0006"] .acf-input select', function(e) { 
        jQuery('.acf-field-5f05b5841a7fa select').empty(); // empty cpt select

        post_type_for_taxo = jQuery(this).val(); 

        jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_populate_taxo_depend_on_post_type', 'post_type': post_type_for_taxo },
            success:function(result_taxo_list) {
                if(result_taxo_list.length != 0 ) {
                    jQuery('.acf-field-5f05b5841a7fa select').append('<option value=""> Selectionner </option>'); 
                    for (const [key, value] of Object.entries(result_taxo_list)) {
                        jQuery('.acf-field-5f05b5841a7fa select').append('<option value="'+key+'">'+value+'</option>'); 
                    }
                } else {
                    jQuery('.acf-field-5f05b5841a7fa select').append('<option value=""> Acune taxonomie </option>'); 
                }
            }
        });
    });
});