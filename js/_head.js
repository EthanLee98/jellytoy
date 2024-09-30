$(document).ready(function () {
    const $toggleBtn = $('.toggle_btn');
    const $dropDownMenu = $('.dropdown_menu');

    // Dropdown menu
    $toggleBtn.on('click', function (event) {
        event.stopPropagation();
        $dropDownMenu.toggleClass('open');
        $toggleBtn.toggleClass('open');
    });

    $(document).on('click', function (event) {
        const isClickInside = $dropDownMenu.is(event.target) || $dropDownMenu.has(event.target).length || $toggleBtn.is(event.target);

        if (!isClickInside) {
            $dropDownMenu.removeClass('open');
            $toggleBtn.removeClass('open');
        }
    });
});

// Toast
const notifications = document.querySelector(".notifications");

const toastDetails = {
    timer: 5000,
    success: {
        icon: 'fa-circle-check',
        defaultText: 'Success: This is a success toast.',
    },
    error: {
        icon: 'fa-circle-xmark',
        defaultText: 'Error: This is an error toast.',
    },
    warning: {
        icon: 'fa-triangle-exclamation',
        defaultText: 'Warning: This is a warning toast.',
    },
    info: {
        icon: 'fa-circle-info',
        defaultText: 'Info: This is an information toast.',
    }
}

const removeToast = (toast) => {
    toast.classList.add("hide");
    if (toast.timeoutId) clearTimeout(toast.timeoutId);
    setTimeout(() => toast.remove(), 500);
}

const createToast = (type, message) => {
    const { icon, defaultText } = toastDetails[type] || toastDetails['info'];
    const toastMessage = message || defaultText;
    const toast = document.createElement("li");
    toast.className = `toast ${type}`;

    toast.innerHTML = `
        <div class="column">
            <i class="fa-solid ${icon}"></i>
            <span>${toastMessage}</span>
        </div>
        <i class="fa-solid fa-xmark" onclick="removeToast(this.parentElement)"></i>
    `;

    notifications.appendChild(toast);
    toast.timeoutId = setTimeout(() => removeToast(toast), toastDetails.timer);
}