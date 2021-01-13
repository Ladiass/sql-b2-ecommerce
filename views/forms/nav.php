<nav class="navbar navbar-expand-sm navbar-light bg-light font-weight-bold px-4">
    <a class="navbar-brand" href="/">B2 Ecommerce</a>
    <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Card</a>
            </li>
            <li class="nav-item dropdown dropdown-menu-right">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php !isset($_SESSION["user_details"]) ? print "User" : print $_SESSION["user_details"]["username"] ?> 
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownId">
                    <?php if(!isset($_SESSION["user_details"])){ ?> 
                        <a class="dropdown-item" href="/views/partials/user.php">Login/Register</a>
                    <?php } ?> 
                    <?php if(isset($_SESSION["user_details"])){ ?> 
                        <a class="dropdown-item" href="#">Payments</a> 
                        <a class="dropdown-item" href="/methods.php?action=logout">Logout</a>
                    <?php } ?> 
                </div>
            </li>
        </ul>
        <!-- <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> -->
    </div>
</nav>