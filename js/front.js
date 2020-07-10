
jQuery(document).ready(function(){   
    //////
    // FILTER BY TERM (FOR DISPLAY CPT)
    ////// 
    jQuery('.sedoo_port_action_btn.cpt_button li').click(function() {
        jQuery('.sedoo_port_action_btn.cpt_button li').removeClass('active');
        jQuery(this).toggleClass('active');
        cpt = jQuery(this).attr('cpt');
        term = jQuery(this).attr('term');
        taxo = jQuery(this).attr('taxo');
        layout = jQuery(this).attr('layout');
        jQuery.ajax({   // AJAX GOES IN sedoo-wppl-portfolio-functions.php
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_filter_display_cpt','layout':layout, 'taxo': taxo, 'cpt': cpt, 'term': term },
            complete:function(result_term_list) {
                if(result_term_list.responseText != '') {
                    jQuery('.sedoo_portfolio_section.section_cpt').html(result_term_list.responseText);
                } else {
                    jQuery('.sedoo_portfolio_section.section_cpt').html('<p class="noresult">Aucun éléments ne correspond à votre recherche</p>');
                }
            }
        });
    });

    //////
    // FILTER BY CPT (FOR DISPLAY CTX)
    ////// 
    jQuery('.sedoo_port_action_btn.ctx_button li').click(function() {
        jQuery('.sedoo_port_action_btn.ctx_button li').removeClass('active');
        jQuery(this).toggleClass('active');
        cpt = jQuery(this).attr('cpt');
        term = jQuery(this).attr('term');
        taxo = jQuery(this).attr('ctx');
        layout = jQuery(this).attr('layout');
        jQuery.ajax({   // AJAX GOES IN sedoo-wppl-portfolio-functions.php
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_filter_display_ctx','layout':layout, 'taxo': taxo, 'cpt': cpt, 'term': term },
            complete:function(result_term_list) {
                if(result_term_list.responseText != '') {
                    jQuery('.sedoo_portfolio_section.section_ctx').html(result_term_list.responseText);
                } else {
                    jQuery('.sedoo_portfolio_section.section_ctx').html('<p class="noresult">Aucun éléments ne correspond à votre recherche</p>');
                }
            }
        });
    });

});