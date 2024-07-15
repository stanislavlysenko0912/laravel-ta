import './bootstrap';

/* ALL FILES IT`s JUST EXAMPLES, THAT API WORKS */

let host = window.location.origin;

async function loadPositions() {
    let host = window.location.origin;
    const tdClass = 'px-2 py-3 text-left text-xs font-medium text-gray-500 text-center';

    let response = await fetch(`${host}/api/v1/positions`);

    if (response.ok) {
        let tableBody = document.getElementById('positions-table-body');
        let positions = (await response.json())['positions'];

        positions.forEach(position => {
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="${tdClass}">${position.id}</td>
                <td class="${tdClass}">${position.name}</td>
            `;

            tableBody.appendChild(tr);
        });
    }
}

let usersPage = 1;
let usersCount = 5;
let totalPages = 1;
let authToken = '';

async function loadUsers() {
    const tdClass = 'px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider';

    let response = await fetch(`${host}/api/v1/users?page=${usersPage}&count=${usersCount}`);

    if (!response.ok) return;

    let data = await response.json();
    let users = data['users'];

    let tableBody = document.getElementById('users-table-body');
    tableBody.innerHTML = '';


    let totalUsers = data.total_users;
    totalPages = data.total_pages;
    let totalUsersElem = document.getElementById('users-show-counter');

    totalUsersElem.innerHTML = `Showing ${data.count} of ${data.total_users} users, page ${usersPage} of ${totalPages}`;

    users.forEach(user => {
        let tr = document.createElement('tr');

        let timeRegistration = new Date(user.registration_timestamp * 1000);


        tr.innerHTML = `
            <td class="${tdClass}">${user.id}</td>
            
            <td class="${tdClass}" style="max-width: 32px;padding-left: 16px;">
                <img src="${user.photo}" alt="photo" class="w-10 h-10 rounded-full">
            </td>
            <td class="${tdClass}">${user.name}</td>
            <td class="${tdClass}">${user.email}</td>
            <td class="${tdClass}">${user.phone}</td>
            <td class="${tdClass}">${user.position}</td>
            <td class="${tdClass}">${timeRegistration.toLocaleDateString()} ${timeRegistration.toLocaleTimeString()}</td>
        `;

        tableBody.appendChild(tr);
    });
}

window.init = function (csrf_token) {
    loadPositions().then();
    loadUsers().then();

    let usersNextBtn = document.getElementById('users-next-btn');
    let usersPrevBtn = document.getElementById('users-prev-btn');

    usersNextBtn.addEventListener('click', function () {
        if (usersPage >= totalPages) return;
        usersPage++;
        loadUsers().then();
    });

    usersPrevBtn.addEventListener('click', function () {
        if (usersPage <= 1) return;
        usersPage--;
        loadUsers().then();
    });

    let getTokenToken = document.getElementById('get-token-btn');

    getTokenToken.addEventListener('click', async function () {
        let response = await fetch(`${host}/api/v1/token`, {
            method: 'GET'
        });

        if (response.ok) {
            let data = await response.json();
            authToken = data['token'];
            alert('Token received');
        }
    });

    let userAddForm = document.getElementById('user-add-form');
    let errorElem = document.getElementById('user-add-error');

    userAddForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        errorElem.classList.add('hidden');

        let formData = new FormData(userAddForm);

        try {
            let response = await fetch(`${host}/api/v1/users`, {
                method: 'POST',
                body: formData,
                headers: {
                    'authorization': authToken,
                    'X-CSRF-TOKEN': csrf_token,
                    'accept': 'application/json',
                }
            });

            let data = await response.json();

            if (response.status > 201) {
                try {
                    errorElem.innerHTML = data['message'];
                    errorElem.classList.remove('hidden');
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
            } else {
                alert('User added');
                loadUsers().then();
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    });
}