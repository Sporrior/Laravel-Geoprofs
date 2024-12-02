<nav class="navbar">
    <div class="navbar-left">
        <div class="logo">
            <a href="/dashboard"><img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon"></a>
        </div>
    </div>

    <div class="navbar-right">
        <div class="profiel-dropdown">
            <p>{{ $user->voornaam }} {{ $user->achternaam }} <i class="ph-bold ph-caret-down"></i></p>
            <div class="dropdown-menu">
                <ul>
                    <li><a href="/profiel"><i class="ph-bold ph-user"></i>Profile</a></li>
                    <li><a href="/instellingen"><i class="ph-bold ph-gear-six"></i>Settings</a></li>
                    <li><a href="/logout"><i class="ph-bold ph-sign-out"></i>Sign Out</a></li>
                </ul>
            </div>
        </div>
        <div class="img-box">
            <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Profielfoto"
                class="nav-profiel-foto">
        </div>
    </div>
</nav>

<script src="https://unpkg.com/phosphor-icons"></script>
<script>
    document.querySelector('.profiel-dropdown').addEventListener('click', function () {
        const dropdownMenu = this.querySelector('.dropdown-menu');
        dropdownMenu.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        const profileDropdown = document.querySelector('.profiel-dropdown');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        if (!profileDropdown.contains(e.target)) {
            dropdownMenu.classList.remove('active');
        }
    });
</script>

<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
        padding: 10px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-left {
        display: flex;
        align-items: center;
    }

    .logo-icon {
        height: 40px;
        margin-right: 20px;
    }

    .nav-links {
        display: flex;
        gap: 20px;
    }

    .nav-link {
        color: #333;
        text-decoration: none;
    }

    .nav-link h4 {
        margin: 0;
    }

    .nav-link.active h4 {
        font-weight: bold;
        color: #007bff;
    }

    .navbar-right {
        display: flex;
        align-items: center;
    }

    .profiel-dropdown {
        position: relative;
        cursor: pointer;
    }

    .profiel-dropdown p {
        margin: 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 215%;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        overflow: hidden;
        z-index: 100;
    }

    .dropdown-menu.active {
        display: block;
    }

    .dropdown-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .dropdown-menu ul li {
        padding: 10px 20px;
    }

    .dropdown-menu ul li a {
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dropdown-menu ul li:hover {
        background-color: #f1f1f1;
    }

    .nav-profiel-foto {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
    }
</style>
