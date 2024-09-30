const fileList = document.querySelector(".file-list");
const fileBrowseButton = document.querySelector(".file-browse-button");
const fileBrowseInput = document.querySelector(".file-browse-input");
const fileUploadBox = document.querySelector(".file-upload-box");

let selectedFile = null; // Only one picture allowed

// Function to create HTML for each file item (including image preview and delete button)
const createFileItemHTML = (file, uniqueIdentifier) => {
    const { name, size, type } = file;
    const extension = name.split(".").pop();
    const formattedFileSize = size >= 1024 * 1024 
        ? `${(size / (1024 * 1024)).toFixed(2)} MB` 
        : `${(size / 1024).toFixed(2)} KB`;

    // Check if the file is an image
    const isImage = type.startsWith('image/');
    const imgSrc = isImage ? URL.createObjectURL(file) : ''; // Create URL for the image preview

    // Generating HTML for file item, including image preview if applicable and a delete button
    return `
        <li class="file-item" id="file-item-${uniqueIdentifier}">
            <div class="file-extension">
                ${isImage ? 
                    `<img src="${imgSrc}" alt="Image preview" class="image-preview" style="max-width: 100%; max-height: 100%;" />` 
                    : extension}
            </div>
            <div class="file-content-wrapper">
                <div class="file-content">
                    <div class="file-details">
                        <h5 class="file-name">${name}</h5>
                        <div class="file-info">
                            <small class="file-size">${formattedFileSize}</small>
                        </div>
                    </div>
                    <button class="delete-button">🗑️</button>
                </div>
            </div>
        </li>`;
};

// Function to handle file selection
const handleSelectedFiles = ([...files]) => {
    if (files.length === 0) return;

    // Only one file allowed
    selectedFile = files[0];
    const uniqueIdentifier = Date.now();
    const fileItemHTML = createFileItemHTML(selectedFile, uniqueIdentifier);

    // Check if fileList exists before using it
    if (fileList) {
        fileList.innerHTML = ''; // Clear previous file entries
        fileList.insertAdjacentHTML("afterbegin", fileItemHTML);
    }

    // Add delete functionality
    const deleteButton = document.querySelector(`#file-item-${uniqueIdentifier} .delete-button`);
    if (deleteButton) {
        deleteButton.addEventListener("click", () => deleteFileItem(uniqueIdentifier));
    }

    // Update the file input with the selected file
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(selectedFile);
    fileBrowseInput.files = dataTransfer.files;  // Set the input file to the dropped file
};

// Function to delete a file item
const deleteFileItem = (uniqueIdentifier) => {
    const fileItem = document.querySelector(`#file-item-${uniqueIdentifier}`);
    if (fileItem) {
        fileItem.remove();
        selectedFile = null; // Remove the selected file reference
        if (fileBrowseInput) {
            fileBrowseInput.value = ''; // Reset the input to allow selecting the same file again
        }
    }
};

// Add event listeners only if the elements exist
if (fileUploadBox) {
    fileUploadBox.addEventListener("drop", (e) => {
        e.preventDefault();
        handleSelectedFiles(e.dataTransfer.files);
        fileUploadBox.classList.remove("active");
    });

    fileUploadBox.addEventListener("dragover", (e) => {
        e.preventDefault();
        fileUploadBox.classList.add("active");
    });

    fileUploadBox.addEventListener("dragleave", () => {
        fileUploadBox.classList.remove("active");
    });
}

if (fileBrowseInput) {
    fileBrowseInput.addEventListener("change", (e) => handleSelectedFiles(e.target.files));
}

if (fileBrowseButton) {
    fileBrowseButton.addEventListener("click", () => {
        fileBrowseInput && fileBrowseInput.click(); // Check fileBrowseInput before accessing it
    });
}


// ============================================================================
// General Functions
// ============================================================================

// Password Input
const passToggleBtns = document.querySelectorAll('.fa-eye-slash');

passToggleBtns.forEach(passToggleBtn => {
    passToggleBtn.addEventListener('click', () => {
        const formGroup = passToggleBtn.closest('.form-group');
        const passwordInput = formGroup.querySelector('input[type="password"], input[type="text"]');

        if (passwordInput) {
            const isPasswordVisible = passwordInput.type === "text";
            passToggleBtn.className = isPasswordVisible ? "fa-solid fa-eye-slash" : "fa-solid fa-eye";
            passwordInput.type = isPasswordVisible ? "password" : "text";
        }
    });
});

// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

});