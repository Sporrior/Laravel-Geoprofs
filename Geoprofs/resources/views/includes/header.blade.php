<style>
    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #4158A6;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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