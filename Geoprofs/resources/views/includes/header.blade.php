<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #4158A6;
        height: 70px;
        position: relative !important;
        top: 0;
        left: 0;
        width: 98%;
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

    .searchBox {
        top: 50%;
        left: 50%;
        background: #2f3640;
        height: 40px;
        border-radius: 40px;
        padding: 10px;

    }

    .searchBox:hover>.searchInput {
        width: 240px;
        padding: 0 6px;
    }

    .searchBox:hover>.searchButton {
        background: white;
        color: #2f3640;
    }

    .searchButton {
        color: white;
        float: right;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #2f3640;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.4s;
    }

    .searchInput {
        border: none;
        background: none;
        outline: none;
        float: left;
        padding: 0;
        color: white;
        font-size: 16px;
        transition: 0.4s;
        line-height: 40px;
        width: 0px;

    }

    .nav-link {
        color: white;
        text-decoration: none;
        white-space: nowrap;
        font-size: 1.1rem;
    }

    .nav-link.active {
        color: #FF8343;
        font-size: 1.1rem;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        position: relative;
        margin-right: 60px;
    }

    .profiel-dropdown {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 12px;
        margin-right: 75px;
    }

    .profiel-dropdown {
        line-height: 1;
        margin: 0;
        color: white;
        font-size: 1.1rem;
        margin-right: 15px;
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
        margin-right: 75px;
        margin-top: 10px;
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
</style>

<nav class="navbar">
    <div class="navbar-left">
        <div class="logo">
            <img src="{{ asset('assets/geoprofs-oranje.png') }}" alt="Logo" class="logo-icon">
        </div>
        <div class="searchBox">

            <input class="searchInput" type="text" name="" placeholder="Zoeken">
            <button class="searchButton" href="#">
                <i class="material-icons">
                    Zoeken
                </i>
            </button>
        </div>
        <a href="#" class="nav-link active">
            Verlof
        </a>
        <a href="#" class="nav-link">
            Ziekmelden
        </a>
        <a href="#" class="nav-link">
            Goedkeuring
        </a>
    </div>

    <div class="navbar-right">
        <div class="profiel-dropdown">
            <p>{{ $user->voornaam }} {{ $user->achternaam }}</p>
        </div>
        <div class="img-box">
            <img id="profielFotoDisplay" src="{{ asset('storage/' . $user->profielFoto) }}" alt="Profielfoto"
                class="nav-profiel-foto">
        </div>
    </div>
    <div class="menu">
        <ul>
            <li><a href="/profiel"><i class="ph-bold ph-user"></i>Profile</a></li>
            <li><a href="/instellingen"><i class="ph-bold ph-gear-six"></i>Settings</a></li>
            <li><a href="/logout"><i class="ph-bold ph-sign-out"></i>Sign Out</a></li>
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