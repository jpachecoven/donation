var Fundify = (function($) {
	var $ = jQuery;

	return {
		init : function() {
			this.header();
			this.menus();
			this.sortCampaigns();
			this.EDDPriceOptions();
			this.campaignTabs();

			if ( FundifyL10n.is_front_page == 1 )
				this.home();
		},

		header : function() {
			$( 'body' ).css( 'padding-top', $( '#header' ).outerHeight() );

			if ( $(window).width() <= 1140 ) {
				$( 'body' ).css( 'padding-top', 0 );
			}
			
			$(window).scroll(function () {
				var y = $(window).scrollTop();    
				
				if ( y >= 400 ) {
					$( '#header' ).addClass( 'mini' );
				} else {
					$( '#header' ).removeClass( 'mini' );
				}
			});

			$(window).resize(function() {
				var x = $(window).width();

				if ( x <= 1140 ) {
					$( 'body' ).css( 'padding-top', 0 );
				} else {
					$( 'body' ).css( 'padding-top', $( '#header' ).outerHeight() );
					$( '#content' ).css( 'overflow', 'hidden' );
				}
			});
		},

		home : function() {
			if ( 'single' == FundifyL10n.hero_style )
				$( '.home-page-featured-single' ).backstretch( $( '.home-page-featured-single' ).data( 'backstretch-source' ) );
		},

		menus : function() {
			$( '.menu-toggle' ).click(function(e) {
				e.preventDefault();

				$( '#menu' ).toggle();
			});
			  
			$(".sort-tabs").on('mouseenter', '.dropdown', function() {
				$('.option-set', this).hide().stop(true, true).fadeIn($.browser.msie && $.browser.version <9 ? 0 : 200);
			}).on('mouseleave', '.dropdown', function() {
				$('.option-set', this).stop(true, true).fadeOut($.browser.msie && $.browser.version <9 ? 0 : 100);
			});
		},

		sortCampaigns : function() {
			if ( $( '#projects' ).length == 0 )
				return;

			var $container = $( '#projects section' );

			this.campaignsIsotope();
			  
			$( '.option-set a' ).click(function(e){
				e.preventDefault();

				var selectorText = $(this).text();
				var selector = $(this).data('filter');
				
				$( '.dropdown .current' ).text(selectorText);
				$( '.dropdown a, .option-set a' ).removeClass( 'selected' );
				
				$(this).addClass( 'selected' );
				
				$container.isotope({ 
					filter: selector 
				});
			});
		},

		campaignsIsotope : function() {
			var $container = $( '#projects section' );

			$container.isotope({
				itemSelector : '.item',
				columnWidth  : 252
			});
		},

		EDDPriceOptions : function() {
			$( '.edd_purchase_submit_wrapper, .edd_price_options_input' ).css( 'display', 'none' );

			$( '.edd_price_options li:not(.inactive)' ).click(function(e) {
				e.preventDefault();

				var pledge = $( this );
				
				$( '.edd_price_options li' )
					.removeClass( 'active' )
					.find( 'input' ).prop( 'checked', false );

				pledge
					.addClass( 'active' )
					.find( 'input' ).prop( 'checked', 'checked' );

				pledge.parents( 'form' ).submit();
			});
		},

		campaignTabs : function() {
			var tabs     = $( '.campaign-tabs' ),
			    overview = $( '.campaign-view-descrption' ),
			    tablinks = $( '.sort-tabs.campaign a' );
			
			tabs.children( 'div' ).hide();
			overview.hide();

			tabs.find( ':first-child' ).show();

			tablinks.click(function(e) {
				if ( $(this).hasClass( 'tabber' ) ) {
					e.preventDefault();

					var link = $(this).attr( 'href' );
						
					tabs.children( 'div' ).hide();
					overview.show();
					tabs.find( link ).show();
					
					$( 'body' ).animate({
						scrollTop: $(link).offset().top - 200
					});
				}
			});
		}
	}
}(jQuery));

jQuery(document).ready(function($) {
	Fundify.init();
	
	/**
	 * Repositions the window on jump-to-anchor to account for
	 * navbar height.
	 */
	var fundifyAdjustAnchor = function() {
		if ( window.location.hash )
			window.scrollBy( 0, -150 );
	};

	$( window ).on( 'hashchange', fundifyAdjustAnchor );
});

jQuery(window).load(function($) {
	Fundify.campaignsIsotope()
});