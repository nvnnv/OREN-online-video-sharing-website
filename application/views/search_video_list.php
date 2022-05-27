<?php 
    // if no login, redirect to login page
    if(!isset($_SESSION['login']))
    {
        header('Location: /oren/user/go_to_login_page');
        exit;
    }
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<body>
	<div class="wrapper hp_1">
		<section class="vds-main">
			<div class="vidz-row">
				<div class="container">
					<div class="vidz_sec">
						<h3>Selected Videos</h3>
						<div class="vidz_list">
							<div class="row">
                                <?php foreach($videos as $video): ?>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 full_wdth">
                                        <div class="videoo">
                                            <div class="vid_thumbainl">
                                                <a href="/oren/video/video_details/<?php echo $video->id ?>" title="">
													<video width="263" height="164"><source src="<?php echo $video->url ?>"/></video>
												</a>
                                            </div><!--vid_thumbnail end-->
                                            <div class="video_info">
                                                <h3><a href="/oren/video/video_details/<?php echo $video->id?>" title=""><?php echo $video->name ?></a></h3>
												<h4><a href="" title="">Loskes</a></h4>
												<span><?php echo $video->views ?>views.<small class="posted_dt"><?php echo $video->create_time ?></small></span>
                                            </div>
                                        </div><!--videoo end-->
								    </div>
                                <?php endforeach;?>
							</div>
						</div><!--vidz_list end-->
					</div><!--vidz_videos end-->
				</div>
			</div><!--vidz-row end-->
		</section><!--vds-main end-->

	</div><!--wrapper end-->

</body>
</html>

<script type='text/javascript'>
let page=1;
$(window).scroll(function() {
	var scrollHeight = $(document).height();
	var scrollPosition = $(window).height() + $(window).scrollTop();
	if($(window).scrollTop() + $(window).height() >= $(document).height()) {
		console.log('bottom', page);
		const keytype = <?php echo $keytype?>;
		const key = <?php echo $keyword?>;
		let url = '';
		if (keytype === 1){
			url = '/oren/video/search_videos_json_by_title'
		}else {
			rl = '/oren/video/search_videos_json_by_user'
		}
		$.ajax(url, {
			dataType: 'json',
			type: 'POST',
			data:{
				page:page,
				per_page:10,	
                key: key,
			},
			success: function (data,status,xhr) {   // success callback function
				console.log(data);
				let video_items = '';
				if (Array.isArray(data.videos))
				{
					data.videos.map(item => {
					video_items += '<div class="col-lg-3 col-md-6 col-sm-6 col-6 full_wdth">'+
										'<div class="videoo">' +
											'<div class="vid_thumbainl">' +
												'<a href="/oren/video/video_details/' + item.id + '" title="">' +
													'<video width="263" height="164"><source src="' + item.url + '"/></video>' +
												'</a>'	+
											'</div>' +
												'<div class="video_info">' +
													'<h3><a href="/oren/video/video_details/' + item.id + '" title="">' + item.title + '</a></h3>' +
													'<h4><a href="" title="">Loskes</a></h4>' +
													'<span>' + item.views + 'views.<small class="posted_dt">' + item.create_time + '</small></span>' +
											'</div>' +
										'</div>' +
									'</div>';
					});
					$('.row').append(video_items);
					page += 1;
				}
			},
			error: function (jqXhr, textStatus, errorMessage) { // error callback 
				console.log('get video failure!');
			}
   		});
    }
});
</script>
