$(() => {
    const form             = document.querySelector('.form-content__form'),
          fakeFileInput    = document.createElement('input'),
          chooseFileButton = document.querySelector('.choose_file'),
          filesContainer   = document.querySelector('.form-content__form .add-file'),
          formFiles        = {};

    fakeFileInput.setAttribute('type', 'file');

    const css = (el, cssObject) => Object.assign(el.style, cssObject);

    const timeStamp = () => Number(new Date);

    const formRequiredFieldsFilled = () => {
        const fields = $(form).find('input.required');

        return [...fields].map(el => !!el.value).filter(el => el).length === fields.length;
    };

    const removeErrorsBorders = () => {
        const requiredInputs = $(form).find('input.required');

        [...requiredInputs].map(el => css(el, {border: '2px solid #c0bec6'}));
    };

    const showEmptyInputsErrorBorders = () => {
        const fields = $(form).find('input.required');

        [...fields]
            .filter(el => !el.value)
            .map(el => css(el, {border: '1px solid red'}));
    };

    const buildFormData = () => {
        const formData = new FormData;

        const inputs = $(form)
            .find('input')
            .not('input[type="button"]');

        [...inputs].map(el => {
            const name  = el.getAttribute('name'),
                  value = el.value;

            formData.append(name, value || '');
        });

        Object
            .values(formFiles)
            .map(file => formData.append('files[]', file));

        return formData;
    };

    const formSubmit = async e => {
        e.preventDefault();

        removeErrorsBorders();

        if (!formRequiredFieldsFilled()) return showEmptyInputsErrorBorders();

        $.ajax({
            method:      'POST',
            url:         'mail/mail.php',
            data:        buildFormData(),
            cache:       false,
            contentType: false,
            processData: false,
            context: document.body
        }).done(function() {
            $(form).trigger('reset');
            
            Object.values(formFiles).map((e, i) => delete formFiles[i]);

            $(filesContainer).empty();
        });
    };

    const removeFileHandler = e => {
        const spanForRemove = e.target.parentElement,
              fileInputId   = spanForRemove.getAttribute('file-id');

        delete formFiles[fileInputId];
    };

    const addFileHandler = e => {
        const files = e.target.files;

        if (files.length === 0) return;

        const file = files[0];

        delete e.target.files;

        fakeFileInput.value = null;

        const span         = document.createElement('span'),
              icon         = document.createElement('i'),
              spanFileName = document.createElement('span'),
              iconRemove   = document.createElement('i'),
              fileId       = 'file_id_' + timeStamp();

        formFiles[fileId] = file;

        spanFileName.innerText = ` ${file.name}`;

        span.classList.add('add-file');
        span.classList.add('add-file__properly');

        icon.classList.add('far');
        icon.classList.add('fa-image');

        iconRemove.classList.add('far');
        iconRemove.classList.add('fa-times-circle');
        iconRemove.addEventListener('click', removeFileHandler);

        span.appendChild(icon);

        span.appendChild(spanFileName);

        span.appendChild(iconRemove);

        span.setAttribute('file-id', fileId);

        filesContainer.appendChild(span);
    };

    form.addEventListener('submit', formSubmit);

    chooseFileButton.addEventListener('click', e => {
        e.preventDefault();

        fakeFileInput.click();
    });

    fakeFileInput.addEventListener('change', addFileHandler);
});