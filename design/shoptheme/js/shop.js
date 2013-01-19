
//Extending main JS core

hw.addtobasket = 'shop/addtobasket/';
hw.deletefrombasketurl = 'shop/deletefrombasket/';

hw.addToBasket = function(pid,variation_id)
{
	$.getJSON(this.formAddPath + this.addtobasket+pid + '/' + variation_id, {} , function(data){				
			$('#basket-'+pid+'-'+variation_id).addClass('ad-basket-ok');       	
			$('#basket-'+pid+'-'+variation_id).removeClass('add-to-cart'); 
			$('#basket-'+pid+'-'+variation_id).unbind('click'); 
			
			$('#basket-'+pid+'-'+variation_id).click(function() {
				hw.deleteFromBasket(pid,$(this).attr('rel'));
			  	return false;
			});
	});
}

hw.deleteFromBasket = function(pid,variation_id)
{
	$.getJSON(this.formAddPath + this.deletefrombasketurl+pid + '/' + variation_id, {} , function(data){				
			$('#basket-'+pid+'-'+variation_id).addClass('add-to-cart');       	
			$('#basket-'+pid+'-'+variation_id).removeClass('ad-basket-ok'); 
			$('#basket-'+pid+'-'+variation_id).unbind('click');	
			
			$('.add-to-cart').click(function() {
			  hw.addToBasket(pid,$(this).attr('rel'));
			  return false;
			});		
	});
}

hw.deleteFromBasketRow = function(pid,variation_id)
{
	$.getJSON(this.formAddPath + this.deletefrombasketurl+pid + '/' + variation_id, {} , function(data) {
			$('#row_basket-'+pid+'-'+variation_id).fadeOut();					
	});
}