$(function() {
	var a1=document.querySelector(".fa.fa-chevron-down");
	var a2=document.querySelector('.menu ul li:nth-child(3) ul');
	var n="10";
	
	$(".slides .slide:first-of-type").addClass("action");
	$(a1).click(function() {
				if(n=='10'){
					$(this).removeClass('rotate');
				$(a2).slideDown();
				n='100';
				}
				else if(n=='100'){
					$(this).addClass('rotate');
					$(a2).slideUp();
				n='10';
				}
				return false;
	});
	//code cho nút previous
	var pre=document.querySelector(".slides .fa-angle-left");
	var next=document.querySelector(".slides .fa-angle-right");
	var cacslide=document.querySelectorAll(".slides>.slide");
	
	var soluongslide=$(cacslide).length;
	var vitri=0;

	$(pre).click(function(event) {
		var slidehientai=$(cacslide[vitri]);
		if(vitri==0){
			vitri=soluongslide-1;
		}
		else if(vitri>0){
			vitri--;
		}
		var slidetieptheo=$(cacslide[vitri]);


		$(slidehientai).addClass('action3');
		$(slidehientai).one('webkitAnimationEnd', function(event) {
			$(slidehientai).removeClass('action');
			$(slidehientai).removeClass('action3');
			
		});

		
		$(slidetieptheo).addClass('action');
		$(slidetieptheo).addClass('action4');
		$(slidetieptheo).one('webkitAnimationEnd', function(event) {
			$(slidetieptheo).removeClass('action4');

		});

	});

	$(next).click(function(event) {
		var slidehientai=$(cacslide[vitri]);
				
		if(vitri==soluongslide-1){
			vitri=0;
		}
		else if(vitri<soluongslide-1){
			vitri++;
		}
		var slidetieptheo=$(cacslide[vitri]);
		
		$(slidehientai).addClass('action1');
		$(slidehientai).one('webkitAnimationEnd', function(event) {
			$(slidehientai).removeClass('action');
			$(slidehientai).removeClass('action1');
			
		});

		
		$(slidetieptheo).addClass('action');
		$(slidetieptheo).addClass('action2');
		$(slidetieptheo).one('webkitAnimationEnd', function(event) {
			$(slidetieptheo).removeClass('action2');

		});
	});

	$(".contact ul li:first-child").click(function(){
				
		$('.container').addClass('a2');
		$(this).hide();
	})
	
});