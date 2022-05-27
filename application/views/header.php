<header>
    <div class="top_bar">
        <div class="container">
            <div class="top_header_content">
                <div class="menu_logo">
                    <a href="/oren/welcome" title="" class="logo">
                        <img src="/oren/images/logo.png" alt="">
                    </a>
                </div><!--menu_logo end-->
                <div class="search_form">
                    <form action="/oren/video/search_videos" method="POST">
                        <input type="text" name="search" placeholder="Search Videos">
                        <button type="submit">
                            <i class="icon-search"></i>
                        </button>
                    </form>
                </div><!--search_form end-->
                <ul class="controls-lv">
                    <!-- <li>
                        <a href="#" title=""><i class="icon-message"></i></a>
                    </li>
                    <li>
                        <a href="#" title=""><i class="icon-notification"></i></a>
                    </li> -->
                    <li class="user-log">
                        <div class="user-ac-img">
                            <?php ?>
                            <i class="large user outline icon"></i>
                        </div>
                        <div class="account-menu">
                            <?php if (isset($_SESSION['login'])):?>
                                <h4><?php echo $_SESSION['username']?></h4>
                                <div class="sd_menu">
                                    <ul class="mm_menu">
                                        <li>
                                            <span>
                                                <i class="icon-user"></i>
                                            </span>
                                            <a href="/oren/user/go_to_info_panel" title="">My Profile</a>
                                        </li>
                                        <li>
                                            <span>
                                                <i class="icon-logout"></i>
                                            </span>
                                            <a href="/oren/user/logout" title="">Sign out</a>
                                        </li>
                                    </ul>
                                </div><!--sd_menu end-->
                            <?php else:?>
                                <div class="sd_menu">
                                    <ul class="mm_menu"></ul>
                                </div>
                            <?php endif;?>
                        </div>
                    </li>
                    <li>
                        <?php if (isset($_SESSION['login'])) : ?>
                            <a href="/oren/video/upload_video" title="" class="btn-default">Upload</a>
                        <?php else: ?>
                            <a href="go_to_login_page" title="" class="btn-default">Sign In</a>
                        <?php endif; ?>
                    </li>
                </ul><!--controls-lv end-->
                <div class="clearfix"></div>
            </div><!--top_header_content end-->
        </div>
    </div><!--header_content end-->
    <div class="btm_bar">
        <div class="container">
            <div class="btm_bar_content">
                <nav>
                    <ul id='category'>
                        <li><a href="Browse_Categories.html" title="">Categories</a></li>
                    </ul>
                </nav><!--navigation end-->
                <div class="clearfix"></div>
            </div><!--btm_bar_content end-->
        </div>
    </div><!--btm_bar end-->
</header><!--header end-->	

<script type='text/javascript'>
$(document).ready(function() {
    $.ajax('/oren/tags/get_all_tags_video', {
        dataType: 'json',
        type: 'GET',
        success: function (data,status,xhr) {   // success callback function
            console.log(data.tags);
            tags = data.tags.slice(0, 12);
            tags.map(ele => {
                    $('#category').append('<li><a href="/oren/video/get_batch_videos_by_tag/' + ele + '" title="">' + ele + '</a></li>');
                })
            if (data.tags.length > 13)
            {
                $('#category').append('<li><a href="/oren/tags/all_tags" title="">More...</a></li>');
            }
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
            console.log('tags failure!');
        }
    })
});
</script>