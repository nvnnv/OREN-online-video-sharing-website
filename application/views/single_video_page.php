
<?php 
    // if no login, redirect to login page
    if(!isset($_SESSION['login']))
    {
        header('Location: /oren/user/go_to_login_page');
        exit;
    }
?>
<div class="wrapper">
	<section class="mn-sec full_wdth_single_video">
		<div class="container">
			<div class="vid-pr">
				<video
				    id=<?php echo $id ?>
				    class="video-js"
				    controls
				    preload="auto"
				    width="640"
				    height="264"
				    data-setup="{}"
				  >
				    <source src=<?php echo $url ?>>
				</video>
			</div><!--vid-pr end-->
			<div class="row">
				<div class="col-lg-12">
					<div class="mn-vid-sc single_video">
						<div class="vid-1">
							<div class="vid-info">
								<h3><?php echo $title ?></h3>
								<div class="info-pr">
									<span><?php echo $views ?> views</span>
									<ul class="pr_links">
										<li id='like-li'>
											<span id='likes_span'>388K</span>
										</li>
										<li id='dislike-li'>
											<span id='dislikes_span'>28K</span>
										</li>
									</ul>
									<div class="clearfix"></div>
								</div><!--info-pr end-->
							</div><!--vid-info end-->
						</div><!--vid-1 end-->
						<div class="abt-mk">
							<div class="info-pr-sec">
								<div class="vcp_inf cr">
									<div class="vc_hd">
										<img id='video_holder_pic' src="images/resources/th5.png" alt="">
									</div>
									<div class="vc_info pr">
										<h4><a href="#" id='video_holder'>ScereBro</a></h4>
										<span>Published on <?php echo $create_time ?></span>
									</div>
								</div><!--vcp_inf end-->							
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
							<div class="about-ch-sec">
								<div class="abt-rw tgs">
									<h4>Tags : </h4>
									<ul id='tag_list'>
									</ul>
								</div>
							</div><!--about-ch-sec end-->
						</div><!--abt-mk end-->
						<div class="cmt-bx">
							<ul class="cmt-pr">
								<li><span>18,386</span> Comments</li>
								<li>
									<span><i class="icon-sort_by"></i><a href="#" title="">Sort By</a></span>
								</li>
							</ul>
							<div class="clearfix"></div>
							<div class="clearfix"></div>
							<div class="vcp_inf pc">
								<div class="vc_hd">
									<img src=<?php echo $_SESSION['user_img'] ?> alt="">
								</div>
								<form action="javascript:void(0);">
									<input type="text" id="comment_input" placeholder="Add a public comment">
									<button type="submit" id="submit_comment">Comment</button>
								</form>
								<div class="clearfix"></div>
								<div class="rt-cmt">
									<a href="#" title="">
										<i class="icon-cancel"></i>
									</a>
									<div class="clearfix"></div>
								</div><!--vcp_inf end-->
							</div><!--cmt-bx end-->
							<ul class="cmn-lst">
							</ul><!--comment list end-->
						</div>
					</div><!--mn-vid-sc end--->
				</div><!---col-lg-9 end-->
			</div>
		</div>
	</section><!--mn-sec end-->

</div><!--wrapper end-->

<script type='text/javascript'>
$(document).ready(function() {
	// get user data
	const id = <?php echo $id?>;
	$.ajax('/oren/user/get_user_info_json', {
		dataType: 'json',
		type: 'POST',
		data:{
			id: id,
		},
		success: function (data,status,xhr) { 
			// id not yet used.
			$('#video_holder').text(data.user.nick_name);
			$("#video_holder_pic").attr("src",data.user.user_img);
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});

	// get tags
	$.ajax('/oren/tags/get_tags_by_video_id', {
		dataType: 'json',
		type: 'POST',
		data:{
			id: id,
		},
		success: function (data,status,xhr) { 
			console.log(data);
			data.tags.map(tag => {
				$('#tag_list').append('<li><a href="#" title="">#' + tag + '</a></li>');
			});
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});

	// get likes
	$.ajax('/oren/likes/likes_and_dislikes', {
		dataType: 'json',
		type: 'POST',
		data:{
			id: id,
		},
		success: function (data,status,xhr) { 
			console.log(data);
			$("#likes_span").text(data.likes);
			$("#dislikes_span").text(data.dislikes);
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});

	// get like or dislike to initiate button flag
	$.ajax('/oren/likes/video_user_like_or_dislike', {
		dataType: 'json',
		type: 'POST',
		data:{
			video_id: id,
			user_id: <?php echo $_SESSION['user_id']?>,
		},
		success: function (data,status,xhr) { 
			console.log(data);
			if (data.status === '0'){
				$('#like-li').prepend('<button title="I like this" id="like" onclick="like();"><i class="thumbs up outline icon"></i></button>');
				$('#dislike-li').prepend('<button title="I dislike this" id="dislike"><i class="thumbs down outline icon"></i></button>');

			}else if (data.status === '1'){
				$('#like-li').prepend('<button title="I like this" id="cancel_like" onclikc="cancel_like();"><i class="thumbs up icon"></i></button>');
				$('#dislike-li').prepend('<button title="I dislike this" id="dislike"><i class="thumbs down outline icon"></i></button>');

			}else if(data.status === '2'){
				$('#like-li').prepend('<button title="I like this" id="like"><i class="thumbs up outline icon"></i></button>');
				$('#dislike-li').prepend('<button title="I dislike this" id="cancel_dislike"><i class="thumbs down icon"></i></button>');
			}
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});

	// get comments
	$.ajax('/oren/comments/get_comments', {
		dataType: 'json',
		type: 'POST',
		data:{
			video_id: id,
			page:0,
			per_page:6,	
		},
		success: function (data,status,xhr) { 
			console.log(data);
			data.comments.map(item => {
				$('.cmn-lst').append(
					'<li>' +
						'<div class="vcp_inf">' + 
							'<div class="vc_hd">' +
								'<img src="' + item.user_img + '" alt="">' +
							'</div>' +
							'<div class="coments">' +
								'<h2>' + item.nickname +  '<small class="posted_dt"> . ' + item.create_time + '</small></h2>' +
								'<p>' + item.comment + '</p>' +
							'</div>' +
						'</div>' +
					'</li>'
				);
			});
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});

});

$("#like-li").click(function() {
	console.log("1111");
	if($("#like").length){
		console.log("222");
		const id = <?php echo $id?>;
	$.ajax('/oren/likes/like', {
		dataType: 'json',
		type: 'POST',
		data:{
			video_id: id,
			user_id: <?php echo $_SESSION['user_id']?>,
		},
		success: function (data,status,xhr) { 
			console.log(data);
			let likes = $("#likes_span").text();
			$("#likes_span").text(Number(likes)+1);
			$('#like').remove();
			$('#like-li').prepend('<button title="I like this" id="cancel_like"><i class="thumbs up icon"></i></button>');
		},
		error: function (jqXhr, textStatus, errorMessage) { 
			console.log(errorMessage);
		}
	});
	}else{
		console.log("333");
		const id = <?php echo $id?>;
	$.ajax('/oren/likes/cancel_like_or_dislike', {
			dataType: 'json',
			type: 'POST',
			data:{
				video_id: id,
				user_id: <?php echo $_SESSION['user_id']?>,
			},
			success: function (data,status,xhr) { 
				console.log(data);
				let likes = $("#likes_span").text();
				$("#likes_span").text(Number(likes)-1);
				$('#cancel_like').remove();
				$('#like-li').prepend('<button title="I like this" id="like"><i class="thumbs up outline icon"></i></button>');
			},
			error: function (jqXhr, textStatus, errorMessage) { 
				console.log(errorMessage);
			}
		});
	}
});
$("#dislike-li").click(function() {
	if($("#dislike").length){
		$.ajax('/oren/likes/dislike', {
			dataType: 'json',
			type: 'POST',
			data:{
				video_id: <?php echo $id?>,
				user_id: <?php echo $_SESSION['user_id']?>,
			},
			success: function (data,status,xhr) { 
				console.log(data);
				let likes = $("#dislikes_span").text();
				$("#dislikes_span").text(Number(likes)+1);
				$("#dislike").remove();
				$('#dislike-li').prepend('<button title="I dislike this" id="cancel_dislike"><i class="thumbs down icon"></i></button>');
			},
			error: function (jqXhr, textStatus, errorMessage) { 
				console.log(errorMessage);
			}
		});
	}else{
		$.ajax('/oren/likes/cancel_like_or_dislike', {
			dataType: 'json',
			type: 'POST',
			data:{
				video_id: <?php echo $id?>,
				user_id: <?php echo $_SESSION['user_id']?>,
			},
			success: function (data,status,xhr) { 
				console.log(data);
				let likes = $("#dislikes_span").text();
				$("#dislikes_span").text(Number(likes)-1);
				$("#cancel_dislike").remove();
				$('#dislike-li').prepend('<button title="I dislike this" id="dislike"><i class="thumbs down outline icon"></i></button>');
			},
			error: function (jqXhr, textStatus, errorMessage) { 
				console.log(errorMessage);
			}
		});
	}
});

let page = 1;
$(window).scroll(function() {
	var scrollHeight = $(document).height();
	var scrollPosition = $(window).height() + $(window).scrollTop();
	if($(window).scrollTop() + $(window).height() >= $(document).height()) {
		// get more comments
		$.ajax('/oren/comments/get_comments', {
			dataType: 'json',
			type: 'POST',
			data:{
				video_id: <?php echo $id?>,
				page:page,
				per_page:6,	
			},
			success: function (data,status,xhr) { 
				console.log(data);
				data.comments.map(item => {
					$('.cmn-lst').append(
						'<li>' +
							'<div class="vcp_inf">' + 
								'<div class="vc_hd">' +
									'<img src="' + item.user_img + '" alt="">' +
								'</div>' +
								'<div class="coments">' +
									'<h2>' + item.nickname +  '<small class="posted_dt"> . ' + item.create_time + '</small></h2>' +
									'<p>' + item.comment + '</p>' +
								'</div>' +
							'</div>' +
						'</li>'
					);
				});
				page+=1;
			},
			error: function (jqXhr, textStatus, errorMessage) { 
				console.log(errorMessage);
			}
		});
	}
});

$("#submit_comment").click(function() {
	// submit a comment
	let comment = $("#comment_input").val();
	if (comment === '' || comment === null)
	{
		return;
	}else{
		$.ajax('/oren/comments/add_comment', {
			dataType: 'json',
			type: 'POST',
			data:{
				video_id: <?php echo $id ?>,
				user_id: <?php echo $_SESSION['user_id'] ?>,
				comment: comment,	
			},
			success: function (data,status,xhr) { 
				console.log(data);
				window.location.reload();
			},
			error: function (jqXhr, textStatus, errorMessage) { 
				console.log(errorMessage);
			}
		});
	}
});
</script>