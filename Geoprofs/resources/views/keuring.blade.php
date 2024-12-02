@if(!$user->id == 2 || $user->id == 3)

@elseif($user->id == 1)
    <script>window.location = "/dashboard";</script>
@endif
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuring Verlofaanvragen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-y: auto;
            height: 100vh;
        }

        .container-admin {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            overflow-y: auto;
        }

        .success-message {
            background-color: #4caf50;
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
            font-size: 14px;
            transition: opacity 0.5s ease, transform 0.3s ease;
            transform: translateY(-20px);
        }

        .leave-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .leave-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .leave-card h3 {
            font-size: 18px;
            color: #333;
            margin: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .leave-details {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 14px;
            color: #555;
        }

        .leave-detail {
            flex: 1 1 50%;
            border-left: 4px solid #ff8c00;
            padding-left: 8px;
            margin-bottom: 8px;
        }

        .leave-detail strong {
            color: #333;
            font-weight: 600;
        }

        .status {
            font-weight: bold;
            color: #4caf50;
        }

        .status.pending {
            color: #ffa000;
        }

        .status.approved {
            color: #4caf50;
        }

        .status.rejected {
            color: #f44336;
        }

        .status-form, .filter-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .status-form select, .filter-form select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f8f8f8;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .status-form select:focus, .filter-form select:focus {
            border-color: #007bff;
            outline: none;
        }

        .status-form button, .filter-form button {
            padding: 8px 14px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .status-form button:hover, .filter-form button:hover {
            background-color: #0056b3;
        }

        .status-form button:active, .filter-form button:active {
            transform: scale(0.98);
        }

            .reason-container {
        margin-top: 10px;
        }

        .reason-container textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            resize: none;
        }

        .dropdown {
  position: relative;
  font-size: 14px;
  color: #333;
  width: 20vw;

  .dropdown-list {
    padding: 12px;
    background: #fff;
    position: absolute;
    top: 30px;
    left: 2px;
    right: 2px;
    box-shadow: 0 1px 2px 1px rgba(0, 0, 0, .15);
    transform-origin: 50% 0;
    transform: scale(1, 0);
    transition: transform .15s ease-in-out .15s;
    max-height: 66vh;
    overflow-y: scroll;
  }

  .dropdown-option {
    display: block;
    padding: 8px 12px;
    opacity: 0;
    transition: opacity .15s ease-in-out;
  }

  .dropdown-label {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 30px;
    background: #fff;
    border: 1px solid #ccc;
    padding: 6px 12px;
    line-height: 1;
    cursor: pointer;

    &:before {
      content: '▼';
      float: right ;
    }
  }

  &.on {
   .dropdown-list {
      transform: scale(1, 1);
      transition-delay: 0s;

      .dropdown-option {
        opacity: 1;
        transition-delay: .2s;
      }
    }

    .dropdown-label:before {
      content: '▲';
    }
  }

  [type="checkbox"] {
    position: relative;
    top: -1px;
    margin-right: 4px;
  }
}

    </style>
</head>

<body>
<div class="container-admin">
        @include('includes.admin-menu')

        <div class="main-content">
            @if(session('success'))
                <div class="success-message" id="successMessage">{{ session('success') }}</div>
            @endif

            <!-- Filter Form -->
            <form method="GET" action="{{ route('keuring.index') }}" class="filter-form">
                <div class="dropdown" data-control="checkbox-dropdown">
                    <label class="dropdown-label">Verlof</label>
                    <div class="dropdown-list">
                        @foreach($types as $type)
                            <label class="dropdown-option">
                                <input type="checkbox" name="types[]" value="{{ $type->id }}"
                                    {{ (is_array(request('types')) && in_array($type->id, request('types'))) ? 'checked' : '' }} />
                                {{ $type->type }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="dropdown" data-control="checkbox-dropdown">
                    <label class="dropdown-label">Gebruiker</label>
                    <div class="dropdown-list">
                        @foreach($users as $dropdownUser)
                            <label class="dropdown-option">
                                <input type="checkbox" name="users[]" value="{{ $dropdownUser->id }}"
                                    {{ (is_array(request('users')) && in_array($dropdownUser->id, request('users'))) ? 'checked' : '' }} />
                                {{ $dropdownUser->voornaam }} {{ $dropdownUser->achternaam }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit">Filter</button>
            </form>

            @foreach($verlofaanvragen->sortByDesc('updated_at') as $aanvraag)
                <div class="leave-card">
                    <h3>{{ optional($aanvraag->user->info)->voornaam }}'s Verlofaanvraag</h3>
                    <div class="leave-details">
                        <div class="leave-detail"><strong>Reden:</strong> {{ $aanvraag->verlof_reden }}</div>
                        <div class="leave-detail"><strong>Start Datum:</strong> {{ $aanvraag->start_datum }}</div>
                        <div class="leave-detail"><strong>Eind Datum:</strong> {{ $aanvraag->eind_datum }}</div>
                        <div class="leave-detail"><strong>Type Verlof:</strong> {{ optional($aanvraag->type)->type }}</div>
                        <div class="leave-detail">
                            <strong>Status:</strong>
                            <span class="status
                            {{ is_null($aanvraag->status) ? 'pending' : ($aanvraag->status == 1 ? 'approved' : 'rejected') }}">
                                {{ is_null($aanvraag->status) ? 'Pending' : ($aanvraag->status == 1 ? 'Goedgekeurd' : 'Weigeren') }}
                            </span>
                        </div>
                        @if($aanvraag->status == 0 && $aanvraag->weigerreden)
                            <div class="leave-detail">
                                <strong>Weigerreden:</strong> {{ $aanvraag->weigerreden }}
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('keuring.updateStatus', $aanvraag->id) }}" method="POST" class="status-form">
                        @csrf
                        <select name="status" id="status-{{ $aanvraag->id }}" class="status-select" data-aanvraag-id="{{ $aanvraag->id }}">
                            <option value="">Selecteer status</option>
                            <option value="1" {{ $aanvraag->status === 1 ? 'selected' : '' }}>Goedkeuren</option>
                            <option value="0" {{ $aanvraag->status === 0 ? 'selected' : '' }}>Weigeren</option>
                        </select>
                        <div id="reason-container-{{ $aanvraag->id }}" class="reason-container" style="display: none;">
                            <textarea name="weigerreden" placeholder="Geef de reden voor weigering"></textarea>
                        </div>
                        <button type="submit">{{ is_null($aanvraag->status) ? 'Verstuur' : 'Wijzigen' }}</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
                successMessage.style.opacity = '1';

                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelects = document.querySelectorAll('.status-select');

            statusSelects.forEach(select => {
                select.addEventListener('change', function () {
                    const aanvraagId = this.getAttribute('data-aanvraag-id');
                    const reasonContainer = document.getElementById(`reason-container-${aanvraagId}`);
                    if (this.value === '0') {
                        reasonContainer.style.display = 'block';
                    } else {
                        reasonContainer.style.display = 'none';
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');

    dropdowns.forEach(dropdown => {
        const label = dropdown.querySelector('.dropdown-label');
        const list = dropdown.querySelector('.dropdown-list');

        label.addEventListener('click', function (e) {
            e.preventDefault();
            dropdown.classList.toggle('on');
            const isExpanded = dropdown.classList.contains('on');
            dropdown.setAttribute('aria-expanded', isExpanded);
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('on');
                dropdown.setAttribute('aria-expanded', false);
            }
        });
    });
});

    </script>
</body>

</html>
