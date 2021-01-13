jQuery(document).ready(function(){   
    var ajax_refresh_cpt = 'any';
    var ajax_refresh_term= jQuery('#portfolio_ajax_infos').attr('term');
    var ajax_refresh_taxo= jQuery('#portfolio_ajax_infos').attr('ctx');
    var ajax_refresh_layout = jQuery('#portfolio_ajax_infos').attr('layout');
    var ajax_refresh_order =  jQuery('#portfolio_ajax_infos').attr('order');
    var ajax_refresh_orderby =  jQuery('#portfolio_ajax_infos').attr('orderby');
    var ajax_refresh_page = 1;
    var ajax_refresh_max_page = 10;

    //////
    // FILTER BY TERM (FOR DISPLAY CPT)
    ////// 
    jQuery('.sedoo_port_action_btn.cpt_button li').click(function() {
        jQuery('.sedoo_port_action_btn.cpt_button li').removeClass('active');
        jQuery(this).toggleClass('active');
        ajax_refresh_page = 0;
        ajax_refresh_cpt = cpt = jQuery(this).attr('cpt');
        ajax_refresh_term = term = jQuery(this).attr('term');
        ajax_refresh_taxo = taxo = jQuery(this).attr('taxo');
        ajax_refresh_layout = layout = jQuery(this).attr('layout');
        ajax_refresh_order = order = jQuery(this).attr('order');
        ajax_refresh_orderby = orderby = jQuery(this).attr('orderby');
        ajax_refresh_max_page = maxpage = jQuery(this).attr('maxpage');
        jQuery.ajax({   // AJAX GOES IN sedoo-wppl-portfolio-functions.php
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_filter_display','maxpage': ajax_refresh_max_page, 'page': 0, 'order': order, 'orderby': orderby, 'layout':layout, 'taxo': taxo, 'cpt': cpt, 'term': term , 'sedoo_portfolio_filter': 'cpt'},
            complete:function(result_term_list) {
                if(result_term_list.responseText != '') {
                    jQuery('.sedoo_portfolio_section.section_cpt').html(result_term_list.responseText);
                    addAndLoadReloadPageButton();
                } else {
                    jQuery('.sedoo_portfolio_section.section_cpt').html('<p class="noresult">Aucun éléments ne correspond à votre recherche</p>');
                }
            }
        });
    });

    function addAndLoadReloadPageButton() {
        jQuery('.sedoo_portfolio_section').append('<div id="button_for_page_load">Click to show more</div>');
        jQuery('#button_for_page_load').click(function(){
            ajax_refresh_page++; 
            jQuery('#button_for_page_load').text('Loading..');
            loadArticle(ajax_refresh_page, ajax_refresh_cpt, ajax_refresh_term, ajax_refresh_taxo,ajax_refresh_layout);          
        });
    }
    //////
    // FILTER BY CPT (FOR DISPLAY CTX)
    ////// 
    jQuery('.sedoo_port_action_btn.ctx_button li').click(function() {
        jQuery('.sedoo_port_action_btn.ctx_button li').removeClass('active');
        jQuery(this).toggleClass('active');
        ajax_refresh_page = 1;
        ajax_refresh_cpt = cpt = jQuery(this).attr('cpt');
        ajax_refresh_term = term = jQuery(this).attr('term');
        ajax_refresh_taxo = taxo = jQuery(this).attr('ctx');
        ajax_refresh_layout = layout = jQuery(this).attr('layout');
        ajax_refresh_order = order = jQuery(this).attr('order');
        ajax_refresh_orderby = orderby = jQuery(this).attr('orderby');
        jQuery.ajax({   // AJAX GOES IN sedoo-wppl-portfolio-functions.php
            url: ajaxurl,
            method: "POST",
            dataType:"json",
            data: { action : 'sedoo_portfolio_filter_display','maxpage': ajax_refresh_max_page, 'page': 0,'order': order, 'orderby': orderby, 'layout':layout, 'taxo': taxo, 'cpt': cpt, 'term': term, 'sedoo_portfolio_filter': 'ctx' },
            complete:function(result_term_list) {
                if(result_term_list.responseText != '') {
                    jQuery('.sedoo_portfolio_section.section_ctx').html(result_term_list.responseText);
                    addAndLoadReloadPageButton();
                } else {
                    jQuery('.sedoo_portfolio_section.section_ctx').html('<p class="noresult">Aucun éléments ne correspond à votre recherche</p>');
                }
            }
        });
    });


    ///////
    // AJAX PAGINATION
    ///////
    addAndLoadReloadPageButton();


    function loadArticle(pageNumber, ajaxcpt, ajaxterm, ajaxtaxo, ajaxlayout){ 
        jQuery.ajax({
          url: ajaxurl,
          type:'POST',
          data: { action : 'sedoo_portfolio_filter_display','maxpage': ajax_refresh_max_page, 'page': pageNumber,'order': ajax_refresh_order, 'orderby': ajax_refresh_orderby, 'layout':ajaxlayout, 'taxo': ajaxtaxo, 'cpt': ajaxcpt, 'term': ajaxterm, 'sedoo_portfolio_filter': 'ctx' },
          complete:function(result_term_list) {
            if(result_term_list.responseText != '') {
                jQuery('#button_for_page_load').remove();
                jQuery('.sedoo_portfolio_section').append(result_term_list.responseText);
                jQuery('#button_for_page_load').text('Click to show more');     
            } else {
            }
            addAndLoadReloadPageButton();
          }
        });
    return false;
    }

});