const url = window.location.href;
const params = new URL(url).searchParams;

if (params.has('success')) {
    let value = params.get('success');
    switch (value) {
        case '1':
            notify(1, "La visibilité de vos TD a été mise à jour.");
            break;
        case '2':
            notify(1, "La liste de vos TD a été mise à jour.");
            break;
        default:
            notify(1, "L'opération a été effectuée avec succès.");
            break;
    }
} else {
}

function notify(type, data) {
    let notif = document.createElement('div');
    let text = document.createElement('h5');
    let color;
    text.textContent = data;
    notif.className = 'notification';
    notif.appendChild(text);
    switch (type) {
        case 1:
            color = '#43c71e';
            break;
        case 2:
            color = '#ffa600';
            break;
        case 3:
            color = '#da1e1e';
            break;
    }
    notif.style.background = color;
    document.getElementById('notification-frame').appendChild(notif);
    setTimeout(() => {
        notif.remove();
    }, 5000);
}