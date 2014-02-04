$(document).ready(function() {
	// initialize scrollable
	$(".scrollable").scrollable({circular: true}).autoscroll(4000).navigator();

	$("#menu").dropit();

	$('.drop_menu li').mouseenter(function(){
		$(this).css('background-color','#b8b8b8');
	}).mouseleave(function(){
		$(this).css('background-color','#8c8c8c');
	});

	konami = new Konami();
    konami.load("http://www.example.com");
	
	this.nbPages = nbPages;
	this.nbPagesPopup = nbPagesPopup;
	this.catId = catId;
});

var currentPage = 1;
var nbPages;
var currentPagePopup = 1;
var nbPagesPopup;
var catId;

function openPopup(baseUrl,id)
{
	//window.open(baseUrl+'/popup?id='+id,"RZO","menubar=no, status=no, scrollbars=no, menubar=no, width=1000, height=660");
	$.fancybox({
			href : baseUrl+'/popup?id='+id,
			autoDimensions : true,
			scrolling   : 'no',
		});

	return false;
}

function changeActiveShow(show)
{
	if(show == 'all')
	{
		$('#this_show').attr('src',$('#this_show').attr('src').replace('_on','_off'));
		$('#all_shows').attr('src',$('#this_show').attr('src').replace('_off','_on'));
	}
	else
	{
		$('#this_show').attr('src',$('#this_show').attr('src').replace('_off','_on'));
		$('#all_shows').attr('src',$('#this_show').attr('src').replace('_on','_off'));
	}
	
	return true;	
}

function closePopupWindow(url)
{
	window.location.href(url);
}

function downloadFile(url)
{
	//e.preventDefault();  //stop the browser from following
    window.open(url, '_blank');
}

function reloadEpisodesList(direction,currentPage2,baseUrl)
{
	$('#ajax_loader').css('display','block');
	
	var postData = { 'direction' : direction, 'currentPage' : this.currentPage, 'nbPages' : this.nbPages }
	var location= baseUrl + '/reload';
	
	if(direction == 'next')
	{
		var newPage = this.currentPage + 1;
		if(newPage > this.nbPages){newPage = 1;}
	}
	else
	{
		var newPage = this.currentPage - 1;
		if(newPage <= 0){newPage = this.nbPages;}
	}
	
	this.currentPage = newPage;
	
	$.ajax({
		type:'POST',
		data:postData,
		url:location,
		success:function(data) {
		  $('#old_episodes').html(data);
		  $('#ajax_loader').css('display','none');
		  $('#current_page').html(newPage);
		  $('#current_page_container').html(newPage);
		}
	  });
}

function reloadEpisodesListPopup(direction,currentPage2,baseUrl)
{
	$('#ajax_loader_popup').css('display','block');
	
	var postData = { 'direction' : direction, 'currentPagePopup' : this.currentPagePopup, 'nbPagesPopup' : this.nbPagesPopup }
	var location= baseUrl + '/reload-popup';
	
	if(direction == 'next')
	{
		var newPage = this.currentPagePopup + 1;
		if(newPage > this.nbPagesPopup){newPage = 1;}
	}
	else
	{
		var newPage = this.currentPagePopup - 1;
		if(newPage <= 0){newPage = this.nbPagesPopup;}
	}
	
	this.currentPagePopup = newPage;
	
	$.ajax({
		type:'POST',
		data:postData,
		url:location,
		success:function(data) {
		  $('#old_episodes_popup').html(data);
		  $('#ajax_loader_popup').css('display','none');
		  $('#current_page_popup').html(newPage);
		  $('#current_page_container_popup').html(newPage);
		}
	  });
}

function reloadThisEpisodesListPopup(direction,currentPage2,baseUrl,showId)
{
	$('#ajax_loader_popup').css('display','block');
	
	var postData = { 'direction' : direction, 'currentPagePopup' : this.currentPagePopup, 'nbPagesPopup' : this.nbPagesPopup, 'showId' : showId }
	var location= baseUrl + '/reload-this-show-episodes';
	
	if(direction == 'next')
	{
		var newPage = this.currentPagePopup + 1;
		if(newPage > this.nbPagesPopup){newPage = 1;}
	}
	else
	{
		var newPage = this.currentPagePopup - 1;
		if(newPage <= 0){newPage = this.nbPagesPopup;}
	}
	
	this.currentPagePopup = newPage;
	
	$.ajax({
		type:'POST',
		data:postData,
		url:location,
		success:function(data) {
		  $('#old_episodes_popup').html(data);
		  $('#ajax_loader_popup').css('display','none');
		  $('#current_page_popup').html(newPage);
		  $('#current_page_container_popup').html(newPage);
		}
	  });
}

function loadThisShowEpisodes(baseUrl){
	var postData = { 'cat_id' : this.catId }
	var location= baseUrl + '/reload-this-show';
	
	$.ajax({
		type:'POST',
		data:postData,
		url:location,
		success:function(data) {
		  $('#old_episodes_popup_container').html(data);
		  $('#ajax_loader_popup').css('display','none');
		  $('#current_page_popup').html(1);
		  $('#current_page_container_popup').html(1);
		}
	  });
}

function reloadAllShow(baseUrl){
	var location= baseUrl + '/reload-all-show';
	
	$.ajax({
		type:'POST',
		url:location,
		success:function(data) {
		  $('#old_episodes_popup_container').html(data);
		  $('#ajax_loader_popup').css('display','none');
		  $('#current_page_popup').html(1);
		  $('#current_page_container_popup').html(1);
		}
	  });
}