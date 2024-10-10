
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
        <a href="#" class="nav-link"><h4>Goedkeuring</h4></a>
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