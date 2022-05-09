<?php 
    // if no login, redirect to login page
    if(!isset($_SESSION['login']))
    {
        header('Location: /oren/user/go_to_login_page');
        exit;
    }
?>
<div class="wrapper">	    
    <div id='video-upload'>
    <section class="upload-videooz">
        <div class="video-file">
            <i class="icon-graphics_05"></i>
            <div style="display: flex;flex-flow: column;justify-content: center;">
                <h3 id='please-upload'>MP4 and AVI only.</h3>
                <form id='file-form' style="display: flex; justify-content:center;max-height:20px;">
                    <label for="file-select" class="custom-file-upload">
                        Select Video
                    </label>
                    <input id="file-select" type="file" accept="video/mp4,video/avi"/>
                    <a href='#' id="file-upload" class="btn-default">
                        Upload Video
                    </a>
                </form>
            </div>
        </div><!--video-file end-->
    </section><!--upload-videooz end-->
	<section class="upload-detail" style='display:none'>
		<div class="container" id='video-upload-div'>
			<h3>Upload Details</h3>
			<div class="vid_thumbainl tr" style="display: flex; justify-content:center;">
				<a href="#" title="">
                    <i class="massive film icon"></i>
				</a>	
			</div><!--vid_thumbnail tr end-->
			<div class="video_info sr">
				<h3><a href="#" id='video-name'>30-Minute Beginner's Strength Training workout</a></h3>
				<h4 id='video-size'>102.6 MB</h4>
				<div class="progress">
				    <div id='upload-progress' class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0">
				      <!-- <span id='upload-progress-span' class="sr-only">70% Complete</span> -->
				    </div>
				</div>
				<!-- <a href="#" title="" class="cancel_vid">
					<i class="icon-cancel"></i>
				</a> -->
				<p> Your Video is still uploading, please keep this page open util it’s done.</p>
                <p style="display: none;" id='error-p'></p>
			</div><!--videoo end-->
			<div class="clearfix"></div>
		</div>
	</section><!--upload-detail end-->
    </div>

	<section class="vid-title-sec">
		<div class="container">
			<form>
				<div class="vid-title">
					<h2 class="title-hd">Video Title </h2>
					<div class="form_field">
						<input id='video-title' type="text" placeholder="Add here (99 characters remaining)">
					</div>
				</div><!--vid-title-->
				<!-- <div class="abt-vidz-pr">
					<h2 class="title-hd"> About </h2>
					<div class="form_field">
						<textarea placeholder="Description"></textarea>
					</div>
				</div> -->
				<div class="abt-tags">
					<div class="row">
						<div class="col-lg-9 col-md-9 col-sm-8 col-12">
							<h2 class="title-hd">Tags (13 Tags Remaining) </h2>
							<div class="form_field pr">
								<input id='video-tags' type="text" placeholder="gaming#PS4#funny">
							</div>
						</div>
					</div>
				</div><!--abt-tags--->
			</form>
		</div>
	</section><!--vid-title-sec-->

    <section>
        <div style="height: 10px;"></div>
        <div class="container" style="display: flex; justify-content: space-evenly">
            <a href="/oren/welcome" title="" class="btn-default">Return</a>
            <a href="#" title="" id="submit-form" class="btn-default">Submit</a>
        </div>
        <div style="height: 50px;"></div>
    </section>
</div><!--wrapper end-->


<script type="text/javascript">
const OKCode = '200';
let filePath = null;
let uploader = new plupload.Uploader({
    browse_button: 'file-select',
    container: 'video-upload-div',
    url : "/oren/video/upload",
    max_file_size : '1024mb',
    multipart: false,
    filters : {
        mime_types: [
            {title : "Videos", extensions : "mp4,avi"},
        ]
    },
    multi_selection: false,
    unique_name: true,
    // Flash settings
    flash_swf_url : '/oren/js//plupload/js/Moxie.swf',
    // Silverlight settings
    silverlight_xap_url : '/oren/js/plupload/js/Moxie.xap',
});
uploader.init();
uploader.bind('UploadProgress', function(up, file) {
    console.log("progress is here.");
    console.log(file.percent);
    $('#upload-progress').css({'width': file.percent+'%'});
});
uploader.bind('FileUploaded', function(up, file, result) {
    console.log("Uplaod complete.");
    console.log(result.response);
    const return_msg = $.parseJSON(result.response);
    //console.log(result.response['err_code'], OKCode); 
    if (return_msg.err_code === OKCode) {
        $('#error-p').css({'display': 'block', 'color': 'green'});
        $('#error-p').text('Successful! You can submit the form now.');
        filePath = return_msg.path;
    } else {
        $('#error-p').css({'display': 'block', 'color': 'red'});
        $('#error-p').text(return_msg.error);
    }
});
uploader.bind('Error', function(up, err) {
    $('#error-p').css({'display': 'block', 'color': 'red'});
    $('#error-p').text(err.code + ' ' + err.message);
});
uploader.bind("FilesAdded", function(up, files) {
    $('#video-name').text(files[0].name);
    $('#video-size').text((files[0].size / (1024 ** 2)) + 'MB');
    $('#please-upload').text('You can upload now.')
});
$("#file-select").on('change', function() {
    let selectedFile = this.files[0];
    if (!String(selectedFile.type).endsWith('video/mp4') && !String(selectedFile.type).endsWith('video/avi')){
        alert("Only mp4 and avi can be accepted.");
        return;
    }
});
$("#file-upload").click(function() {
    $('.upload-videooz').css('display', 'none');
    $('.upload-detail').css('display', 'block');
    uploader.start();
});
$('#submit-form').click(function() {
    if (filePath === null) {
        alert('Please upload a video first.');
        return;
    }
    $.ajax('/oren/video/upload_video_info_form',
    {
        dataType: 'json',
        type: 'POST',
        data: {
            title: $('#video-title').val(),
            tags: $('#video-tags').val(),
            file_url: filePath,
        },
        success: function (data,status,xhr) {   // success callback function
            alert('You uploaded a video successfully.');
            // error-p
            $('#error-p').css({'display': 'none'});
            $('#error-p').text('');
            // panel
            $('.upload-videooz').css('display', 'block');
            $('.upload-detail').css('display', 'none');
            // progress reset
            $('#upload-progress').css({'width': 0+'%'});
            $('#please-upload').text('MP4 and AVI only.');
            $('#video-title').text('');
            $('#video-tags').text('');
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            alert('Error:' + errorMessage);
        }
    });
});
</script>