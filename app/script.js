document.addEventListener("DOMContentLoaded", () => {
    let hash = window.location.hash;
    const root = document.getElementById("root");
    const links = document.querySelectorAll(".header__link");
    let currentScript = null;

    hashHandler(hash);

    function clickLink() {
        hash = window.location.hash;
        hashHandler(hash);
    }

    links.forEach((link) => {
        link.addEventListener("click", () => {
            setTimeout(clickLink, 1);
        });
    });


    const logout = document.querySelector('.logout');
    logout.addEventListener('click', async (e) => {
        await e.preventDefault();
        if (confirm("Вы уверены, что хотите выйти из аккаунта?")) {
            window.location.href = 'https://pet-project/app/logout.php';
        }

    })


    function hashHandler(hash) {
        if (hash === "#tasks") {
            fetch("../app/templates/task.php")
                .then((response) => response.text())
                .then((html) => {
                    root.innerHTML = html;
                    initializeTasks();
                });
        }
        if (hash === "#auth") {
            fetch("../app/templates/auth.html")
                .then((response) => response.text())
                .then((html) => {
                    root.innerHTML = html;
                    initializeAuth();
                });
        }
        if (hash === "#reg") {
            fetch("../app/templates/reg.html")
                .then((response) => response.text())
                .then((html) => {root.innerHTML = html; initializeReg()});
        }
    }

    function initializeTasks() {
        const add_task_btn = document.querySelector(".add_tasks");
        const modal = document.querySelector(".modal");
        const close_modal = document.querySelector(".form__button--close");

        close_modal.addEventListener("click", () => {
            if (modal.classList.contains("visible")) {
                modal.classList.remove("visible");
            }
        });

        add_task_btn.addEventListener("click", () => {
            modal.classList.toggle("visible");
        });

        const close_btns = document.querySelectorAll('.tasks__card--close');
        close_btns.forEach(btn => {
            const form = btn.parentElement;
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if(confirm("Вы уверены, что хотите удалить задачу?")) {
                    form.submit();
                }
            })
        })
    }

    function initializeAuth() {
        const fields = [
            {
                name: document.querySelector("#auth_name"),
                validate: function () {
                    if (!this.name.value.length && this.name.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            },
            {
                password: document.querySelector("#auth_password"),
                validate: function () {
                    if (!this.password.value.length && this.password.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            },
        ];

        function renderError(field, error) {
            const form_error = field
                .closest(".form__label")
                .querySelector(".form__error");
            if (form_error) {
                form_error.textContent = error;
            }
            checkError();
        }

        fields.forEach((field) => {
            if (field.name) {
                field.name.addEventListener("input", () => {
                    renderError(field.name, field.validate());
                });
                field.name.addEventListener("blur", () => {
                    field.name.blur = true;
                    renderError(field.name, field.validate());
                });
            }

            if (field.password) {
                field.password.addEventListener("input", () => {
                    renderError(field.password, field.validate());
                });
                field.password.addEventListener("blur", () => {
                    field.password.blur = true;
                    renderError(field.password, field.validate());
                });
            }
        });

        const btn = document.querySelector('.form__button--auth');
        const errors = document.querySelectorAll('.form__error');
        function checkError() {
            let hasError = false;

            errors.forEach(error => {
                if (error.textContent.trim() !== '') {
                    hasError = true;
                }
            });
        
            btn.disabled = hasError;
        }
    }

    function initializeReg() {
        const fields = [
            {
                name: document.querySelector("#reg_name"),
                validate: function () {
                    if (!this.name.value.length && this.name.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            },
            {
                email: document.querySelector("#reg_email"),
                validate: function () {
                    if (!this.email.value.length && this.email.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            },
            {
                phone: document.querySelector("#reg_phone"),
                validate: function () {
                    if (!this.phone.value.length && this.phone.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            },
            {
                password: document.querySelector("#reg_password"),
                validate: function () {
                    if (this.password.value.length < 10 && this.password.blur) {
                        return "Пароль должен быть не меньше 10 символов";
                    }
                    return "";
                },
            },
            {
                password_confirm: document.querySelector("#reg_password_confirm"),
                validate: function () {
                    if (!this.password_confirm.value.length && this.password_confirm.blur) {
                        return "Поле не может быть пустым";
                    }
                    return "";
                },
            }
        ];

        function renderError(field, error) {
            const form_error = field
                .closest(".form__label")
                .querySelector(".form__error");
            if (form_error) {
                form_error.textContent = error;
            }
            checkError();
        }

        let password_value = null;

        fields.forEach((field) => {
            if (field.name) {
                field.name.addEventListener("input", () => {
                    renderError(field.name, field.validate());
                });
                field.name.addEventListener("blur", () => {
                    field.name.blur = true;
                    renderError(field.name, field.validate());
                });
            }

            if (field.email) {
                field.email.addEventListener("input", () => {
                    renderError(field.email, field.validate());
                });
                field.email.addEventListener("blur", () => {
                    field.email.blur = true;
                    renderError(field.email, field.validate());
                });
            }

            if (field.phone) {
                field.phone.addEventListener("input", () => {
                    renderError(field.phone, field.validate());
                });
                field.phone.addEventListener("blur", () => {
                    field.phone.blur = true;
                    renderError(field.phone, field.validate());
                });
            }

            if (field.password) {
                field.password.addEventListener("input", () => {
                    renderError(field.password, field.validate());
                    password_value = field.password.value;
                });
                field.password.addEventListener("blur", () => {
                    field.password.blur = true;
                    renderError(field.password, field.validate());
                    password_value = field.password.value;
                });
            }

            if (field.password_confirm) {
                field.password_confirm.addEventListener("input", () => {
                    renderError(field.password_confirm, field.validate());
                    if (field.password_confirm.value !== password_value) {
                        renderError(field.password_confirm, 'Пароли не совпадают')
                    }
                });
                field.password_confirm.addEventListener("blur", () => {
                    field.password_confirm.blur = true;
                    renderError(field.password_confirm, field.validate());
                    if (field.password_confirm.value !== password_value) {
                        renderError(field.password_confirm, 'Пароли не совпадают')
                    }
                });
            }
        });

        const btn = document.querySelector('.form__button--reg');
        const errors = document.querySelectorAll('.form__error');
        function checkError() {
            let hasError = false;

            errors.forEach(error => {
                if (error.textContent.trim() !== '') {
                    hasError = true;
                }
            });
        
            btn.disabled = hasError;
        }
    }
});
