import FormRules from "../Classes/formRules";
let form : FormRules;
let errorMessages = {
        email :['Required Field.','invalid email address'],
        pass : ['The password is required.',"Min of 8 chars and max of 16","Secure pass is required"]
}

export function main(){
    form = new FormRules({
        form: 'logInForm',
        fields: [
            {
                name: 'email',
                errorMessages: errorMessages['email'],
                validation: ['required','validEmail'],
                asyncValidation: [['/api/auth/validate-registered-email','email']],
            },
            {
                name: 'pass',
                errorMessages: [],
                validation: [],
                asyncValidation: [],
            },
        ]
    });

    if(window['formDataJson'].email){
        (<HTMLInputElement>document.querySelector(`input[name="email"]`)).value = window['formDataJson'].email;
        (<HTMLInputElement>document.querySelector(`input[name="pass"]`)).value = window['formDataJson'].pass;
        (<HTMLInputElement>document.querySelector(`input[name="rme"]`)).checked = true;
        let event = document.createEvent('HTMLEvents');
        event.initEvent('change', true, false);
        (<HTMLInputElement>document.querySelector(`input[name="email"]`)).dispatchEvent(event);
        (<HTMLInputElement>document.querySelector(`input[name="pass"]`)).dispatchEvent(event);
    }

    console.log('[###] User Log In');
}

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}