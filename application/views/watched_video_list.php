
<?php 
    // if no login, redirect to login page
    if(!isset($_SESSION['login']))
    {
        header('Location: /oren/user/go_to_login_page');
        exit;
    }
?>
<div class="wrapper">
    <section class="user-account">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar">
                        <div class="widget video_info pr sp">
                            <span class="vc_hd">
                                <img src=<?php echo $_SESSION['user_img']?>  style='width:96px' alt="">
                            </span>
                            <h4><?php echo $_SESSION['nick_name']?></h4>
                            <p><?php echo $_SESSION['username']?></p>
                        </div><!--video_info pr-->
                        <div class="widget account">
                            <h2 class="hd-uc"> <i class="icon-user"></i> Account</h2>
                            <ul>
                                <li><a href='/oren/user/go_to_change_pro_pic' id='change-pro-pic' href="#">Change Profile Pic</a></li>
                                <li><a href='/oren/user/go_to_change_password' id='change-psw' href="#">Change Password</a></li>
                                <li><a id='change-email' href="#">Change Email</a></li>
                            </ul>
                        </div><!--account end-->
                        <div class="widget vid-ac">
                            <h2 class="hd-uc"><i class="icon-play"></i>Videos </h2>
                                <ul>
                                <li><a href="/oren/video/uploaded_video_list/0">Uploaded Videos</a></li>
                                <li><a href="/oren/video/watched_history/0">Watched Videos</a></li>
                            </ul>
                        </div><!--vid-ac end-->
                    </div><!--sidebar end-->
                </div>
                <div class="col-lg-9">
                    <div class="video-details">
                        <div class="latest_vidz">
                            <div class="latest-vid-option">
                                <h2 class="hd-op"> Latest Watched Videos</h2>
                            </div><!--latest-vid-option end-->
                            <div class="vidz_list">
                                <?php foreach($videos as $item) : ?>
                                <div class="tb-pr">
                                    <div class="row">
                                        <div class="col-xl-8 col-lg-9 col-md-9 col-sm-12">
                                            <div class="tab-history acct_page">
                                                <div class="videoo">
                                                    <div class="vid_thumbainl ms br">
                                                        <video width='250px' height='140px' controls>
                                                            <source src=<?php echo $item->url?> >
                                                        </video>
                                                    </div><!--vid_thumbnail end-->
                                                    <div class="video_info ms br">
                                                        <h3><a href="" title=""><?php echo $item->name ?></a></h3>
                                                        <h4><?php echo $item->create_time?></h4>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div><!--videoo end-->
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-3 col-md-3 col-sm-12">
                                            <div class="icon-list">
                                                <ul>
                                                    <li><a href="#" title=""><i class="icon-play"></i></a></li>
                                                    <li><a href="#" title=""><i class="icon-cancel"></i></a></li>
                                                </ul>
                                            </div><!--icon-list end-->
                                        </div>
                                    </div>
                                </div><!--tb-pr end-->
                                <?php endforeach ; ?>
                            </div><!--vidz_list end-->
                            <p><?php echo $links; ?></p>
                        </div><!--latest_vidz end-->
                    </div><!--video-details end-->
                </div>
            </div>
        </div>
    </section><!--user-account end-->
</div><!--wrapper end-->

