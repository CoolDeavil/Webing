exports.baseCode = () => {
    return {
        main: [
            './assets/ts/main',
            './assets/scss/main',
        ],
        authLog :[
            './assets/ts/Author/login',
            './assets/scss/authForm.scss',
            './assets/scss/formValidation.scss',
        ],
        avatarUpdate :[
            './assets/ts/Author/avatarCrop.ts',
            './assets/scss/avatarCrop.scss',
        ],
        usrRegister :[
            './assets/ts/Author/registerUser',
            './assets/scss/dropSelector',
            './assets/scss/formValidation',
            './assets/scss/authForm',

        ],
        usrDash :[
            './assets/ts/Author/dashboard',
            './assets/scss/dropSelector.scss',
        ],

    }
};
