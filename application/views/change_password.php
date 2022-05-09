
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
                        </div><!--latest_vidz end-->
                        <div style='margin-top: 30px; margin-left: 20px;' class="change-psw">
                            <h2 class="hd-op">Change password</h2>
                            <?php if(isset($error)):?>
                                <h2 class='hd-op'><?php echo $error;?></h2>
                            <?php endif;?>
                            <form method='post' action='/oren/user/change_password'>
                                <div class="ch-pswd">
                                    <input type="password" name="old_password" placeholder="Old Password">
                                </div><!--ch-pswd end-->
                                <div class="ch-pswd">
                                    <input type="password" name="new_password" placeholder=" New Password">
                                </div><!--ch-pswd end-->
                                <div class="ch-pswd">
                                    <input type="password" name="confirm_n_password" placeholder="Confirm New Password">
                                </div><!--ch-pswd end-->
                                <div class="ch-pswd">
                                    <button type="submit"> Update</button>
                                </div><!--ch-pswd end-->
                            </form>
                        </div><!--change-pswd end-->
                    </div><!--video-details end-->
                </div>
            </div>
        </div>
    </section><!--user-account end-->
</div><!--wrapper end-->
<script type="text/javascript">

</script>
