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
            <a href="#" class="nav-link active">Verlof</a>
            <a href="#" class="nav-link">Ziekmelden</a>
            <a href="#" class="nav-link">Goedkeuring</a>
        </div>

        <div class="navbar-right">
            <a href="{{ route('profiel.show') }}">
                <img src="{{ asset('images/profile-placeholder.jpg') }}" alt="User Profile" class="profile-pic">
            </a>
        </div>
    </nav>