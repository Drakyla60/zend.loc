<!-- Profile Sidebar -->
<div class="col-lg-3">
    <!-- Sidebar Navigation -->
    <?php  $avatar = (null != $user->getAvatar()) ? $user->getAvatar() : 'no-avatar.png';?>
    <img src="<?= $this->basePath('img/avatar/' . $avatar )?>" width="100%" alt="<?= $user->getFullName()?>" class="mb-2">
    <br>
    <ul class="list-group">
        <a href="<?=$this->url('profile') ?>" class="list-group-item  justify-content-between <?= $this->url() == '/profile' ? 'active' : '' ?>">
            <span><i class="icon-cursor"></i> Profile</span>
        </a>
        <a href="<?=$this->url('profile_settings') ?>" class="list-group-item justify-content-between <?= $this->url() == '/profile/settings' ? 'active' : '' ?>">
            <span><i class="icon-settings"></i> Settings</span>
<!--            <span class="u-label g-font-size-11 g-bg-white g-color-main g-rounded-20 g-px-8">3</span>-->
        </a>
<!--        <a href="#" class="list-group-item  justify-content-between">-->
<!--            <span><i class="icon-bubbles"></i> Comments</span>-->
<!--            <span class="u-label g-font-size-11 g-bg-pink g-rounded-20 g-px-8">24</span>-->
<!--        </a>-->
<!--        <a href="#" class="list-group-item  justify-content-between" >-->
<!--            <span><i class="icon-fire"></i> History</span>-->
<!--        </a>-->
    </ul>
    <!-- End Sidebar Navigation -->
</div>
<!-- End Profile Sidebar -->