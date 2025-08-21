// visitors_list.js
// Fetch and display visitors for today, yesterday, or this month
function fetchVisitorsList(type) {
    let url = '';
    if (type === 'today') url = 'fetch_visitors_today.php';
    else if (type === 'yesterday') url = 'fetch_visitors_yesterday.php';
    else if (type === 'month') url = 'fetch_visitors_this_month.php';
    else return;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('visitors-list-body');
            tableBody.innerHTML = '';
            data.forEach(visitor => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${visitor.name}</td>
                    <td>${visitor.contact}</td>
                    <td>${visitor.purpose}</td>
                    <td>${visitor.entry_time || visitor.visit_date}</td>
                    <td>${visitor.exit_time ? 'Exited' : 'In College'}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            document.getElementById('visitors-list-body').innerHTML = `<tr><td colspan='5'>Error loading visitors</td></tr>`;
        });
}

function getQueryType() {
    const params = new URLSearchParams(window.location.search);
    return params.get('type') || 'today';
}

// Add event listeners for dashboard chart clicks
window.addEventListener('DOMContentLoaded', function() {
    fetchVisitorsList(getQueryType());
    document.getElementById('link-today').onclick = function() { fetchVisitorsList('today'); };
    document.getElementById('link-yesterday').onclick = function() { fetchVisitorsList('yesterday'); };
    document.getElementById('link-this-month').onclick = function() { fetchVisitorsList('month'); };
});
