<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #4158A6;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        height: 70px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 999;
    }

    .navbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .logo-icon {
        width: 140px;
        height: auto;
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
    }

    .nav-link h4 {
        color: white;
        text-decoration: none;
        white-space: nowrap;
    }

    .nav-link.active h4 {
        color: #FF8343;
        font-weight: bold;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        position: relative;
    }

    .profiel-dropdown {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 12px;
    }

    .profiel-dropdown h4 {
        line-height: 1;
        margin: 0;
        color: white;
    }

    .nav-profiel-foto {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 200px;
        background: #fff;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transform: translateY(-10px);
        visibility: hidden;
        transition: opacity 300ms, transform 300ms;
        z-index: 10;
    }

    .menu.active {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
    }

    .menu ul {
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
    }

    .menu ul li {
        list-style: none;
        padding: 10px 20px;
        transition: background 0.3s;
    }

    .menu ul li:hover {
        background: #eee;
    }

    .menu ul li a {
        text-decoration: none;
        color: #000;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .menu ul li a i {
        font-size: 1.2em;
    }

    @media (max-width: 768px) {
        .search-bar {
            width: 150px;
        }

        .navbar-left {
            flex-direction: column;
            align-items: flex-start;
        }
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
        <a href="#" class="nav-lik active"><h4>Verlof</h4></a>
        <a href="#" class="nav-link"><h4>Ziekmelden</h4></a>
        <a href="#" class="nav-link"><h4>Goedkeuring</h4></a>n
    </div>

    <div class="navbar-right">
        <div class="profiel-dropdown">
            <h4>Katherine Cooper</h4>
            <div class="img-box">
                <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Profielfoto"
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
    </div>
</nav>

<script src="https://unpkg.com/phosphor-icons"></script>
<script>
    let profielDropdown = document.querySelector('.profiel-dropdown');
    let menu = document.querySelector('.menu');

    profielDropdown.onclick = function () {
        menu.classList.toggle('active');
    }
</script>