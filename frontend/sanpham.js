$(function() {
	var a1=document.querySelector(".fa.fa-chevron-down");
	var a2=document.querySelector('.menu ul li:nth-child(3) ul');
	var n="10";
	
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
	
		$('.submit').click(function(){
			var duongdan="http://localhost/khachhang/index.php/sanpham/giohang";
			
			$.ajax({
				url: duongdan,
				type: 'POST',
				dataType: 'json',
				data: {
					id: $('.idsp').val(),
					soluong:$('.count').val()
				},
			})
			.done(function() {
				console.log("success");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
				//chuyển hướng đến 1 trang khác
				
				window.location.href="http://localhost/khachhang/index.php/sanpham/laydulieugiohang";
			});
			
		})
		$('span.fa.fa-user').click(function(){
					
			$('.giaodien').toggleClass('a1');
		})
		$(".contact ul li:first-child").click(function(){
				
		$('.container').addClass('a2');
		$(this).hide();
	})
	});
	
