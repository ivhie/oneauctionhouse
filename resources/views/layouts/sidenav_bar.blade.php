
<?php
use App\Models\AuctionItems;
$count = new AuctionItems;
?>
<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='dashboard')?'collapsed':''; ?>" href="/dashboard">
        <i class="bi bi-grid"></i>
        <span>Dashboard </span>
      </a>
    </li><!-- End Dashboard Nav -->
    <li class="nav-heading">Auction Items</li>
     <!--
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='auctions')?'collapsed':''; ?>" href="/auctions">
        <i class="bi bi-basket3"></i>
        <span>Auction Items</span>
      </a>
    </li>
    -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='unfulfilled')?'collapsed':''; ?>" href="/unfulfilled-auctions">
        <i class="bi bi-journal-minus"></i>
        <span>Unfulfilled Auctions <span class="badge rounded-pill bg-danger"><?php echo $count->CountAuction('unfulfilled'); ?></span></span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='pending')?'collapsed':''; ?>" href="/pending-approvals">
        <i class="bi bi-journals"></i>
        <span>Pending Approvals <span class="badge rounded-pill bg-danger"><?php echo $count->CountAuction('pending'); ?></span></span>
      </a>
    </li><!-- End Login Page Nav -->
   
    
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='active')?'collapsed':''; ?>" href="/active-auctions">
        <i class="bi bi-journal-text"></i>
        <span>Active Auctions</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='completed')?'collapsed':''; ?>" href="/completed-auctions">
        <i class="bi bi-journal-check"></i>
        <span>Completed Auctions</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='post')?'collapsed':''; ?>" href="/post-auctions">
        <i class="bi bi-journal-arrow-up"></i>
        <span>Post-Auction Listings <span class="badge rounded-pill bg-danger"><?php echo $count->CountAuction('post'); ?></span></span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-heading">Related Menus</li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='email-template')?'collapsed':''; ?>" href="/email-template">
        <i class="bi bi-briefcase"></i>
        <span>Email Template</span>
      </a>
    </li><!-- End Email Template Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='bidders')?'collapsed':''; ?>" href="/bidders">
        <i class="bi bi-people"></i>
        <span>Bidders</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='bidding')?'collapsed':''; ?>" href="/bidding">
        <i class="bi bi-currency-dollar"></i>
        <span>Bidding</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='comments')?'collapsed':''; ?>" href="/comments">
        <i class="bi bi-file-earmark-text"></i>
        <span>Bidding Comments</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='users')?'collapsed':''; ?>" href="/users">
        <i class="bi bi-people"></i>
        <span>Users</span>
      </a>
    </li><!-- End Login Page Nav -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('logout.perform') }}">
        <i class="bi bi-box-arrow-in-left"></i>
        <span>Logout</span>
      </a>
    </li><!-- End Login Page Nav -->
    <!--
    <li class="nav-item">
      <a class="nav-link <?php echo ($page['menu'] !='email-template-test')?'collapsed':''; ?>" href="/email-template-test">
        <i class="bi bi-box-arrow-up-right"></i><span>Email Testing</span>
      </a>
    </li>
  -->
    

   

   

</ul>