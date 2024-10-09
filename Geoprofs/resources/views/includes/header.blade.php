<style>
    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .nav-profiel-foto {
        width: 30%;
        margin-left: 10%;
    }

    .navbar-right {
        width: 30%;
        display: flex;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #4158A6;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        height: 70px;
    }

    .navbar-left {
        display: flex;
        align-items: center;
    }

    .logo-icon {
        width: 160px;
    }

    .search-container {
        position: relative;
    }

    .search-bar {
        padding: 8px 12px;
        border-radius: 20px;
        border: 1px solid #ddd;
        width: 250px;
        background-color: #f0f0f7;
        font-size: 14px;
        margin-left: 10%;
    }

    .navbar-center {
        display: flex;
        gap: 20px;
        margin-right: 46%;
    }

    .nav-link {
        color: white;
        font-size: 16px;
        text-decoration: none;
        font-weight: 500;
    }

    .nav-link.active {
        color: #FF8343;
    }

    .navbar-right .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
    }
    /* profile menu */

    .profiel-dropdown {
        display: flex;
        align-items: center;
        cursor: pointer;
        /* align-content: end; */
        gap: 12px;
    }

    .profiel-dropdown h3 {
        text-align: end;
        line-height: 1;
        font-weight: 600;
    }

    .profiel-dropdown p {
        line-height: 1;
        font-size: 14px;
        opacity: .6;
    }

    .profiel-dropdown .img-box {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
    }

    .profiel-dropdown .img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* menu (the right one) */

    .menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0px;
        width: 200px;
        min-height: 100px;
        background: #fff;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
        opacity: 0;
        transform: translateY(-10px);
        visibility: hidden;
        transition: 300ms;,transform: 300ms;
    }


    .menu.active {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
    }

    /* menu links */

    .menu ul {
        position: relative;
        display: flex;
        flex-direction: column;
        z-index: 10;
        background: #fff;
    }

    .menu ul li {
        list-style: none;
    }

    .menu ul li:hover {
        background: #eee;
    }

    .menu ul li a {
        text-decoration: none;
        color: #000;
        display: flex;
        align-items: center;
        padding: 15px 20px;
        gap: 6px;
    }

    .menu ul li a i {
        font-size: 1.2em;
    }
</style>




<nav class="navbar">
    <div class="navbar-left">
        <div class="logo">
            <img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon">
        </div>
        <div class="search-container">
            <input type="text" placeholder="Search for anything..." class="search-bar">
        </div>
    </div>

    <div class="navbar-center">
        <a href="#" class="nav-link ">Verlof</a>
        <a href="#" class="nav-link">Ziekmelden</a>
        <a href="#" class="nav-link">Goedkeuring</a>
    </div>

    <div class="navbar-right">
        <div class="profiel-dropdown">
            <h3>Katherine Cooper</h3>
        </div>
        <div class="img-box">
            <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Gebruikersprofiel"
                class="nav-profiel-foto">
        </div>
    </div>
    <div class="menu">
        <ul>
            <li><a href="#"><i class="ph-bold ph-user"></i>Profile</a></li>
            <li><a href="#"><i class="ph-bold ph-gear-six"></i>Settings</a></li>
            <li><a href="#"><i class="ph-bold ph-sign-out"></i>Sign Out</a></li>
        </ul>
    </div>
</nav>

<script>
    let profielDropdown = document.querySelector('.profiel-dropdown');
    let menu = document.querySelector('.menu');

    profielDropdown.onclick = function () {
        menu.classList.toggle('active');
    }
</script>