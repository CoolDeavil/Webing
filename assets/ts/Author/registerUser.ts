import FormValidate from '../Classes/formValidate'
import IFormField from "../Interfaces/iFormField";
import ICustomElement from "../Interfaces/iCustomElement";
import IValidateResult from "../Interfaces/iValidateResult";
import SelectBOX from "../Classes/selectBox";

if (document.readyState === 'complete') {
    main()
} else {
    document.addEventListener('DOMContentLoaded', main);
}


let errorMessages =
    {
        name: [
            'Field is required',
            'Min chars are 4 Max chars are 14',
        ],
        email: [
            'Field is required',
            'The email is invalid',
        ],
        pass:[
            'Password is required',
            'At least 8 chars Max of 16 chars',
            'Min 8 char, min 1 lower , min 1 High and a number or special character'
        ],
        cPass:[
            'Password is required',
            'At least 8 chars Max of 16 chars',
            'Min 8 char, min 1 maiuscula, min 1 minuscula e um digito ou caracter especial3',
            'The passwords dont Match'
        ],
    };

let watchFields: IFormField[] =  [
    {
        name: "name",
        validation: [
            'required',
            'range(4,14)',
            noAdminAllowed,
        ],
        errorMessages: errorMessages['name'],
        asyncValidation: [['/api/auth/unique-user','userName']],
    },
    {
        name: "email",
        validation: ['required','validEmail' ],
        errorMessages: errorMessages['email'],
        asyncValidation: [['/api/auth/unique-email','email']],
    },
    {
        name: "pass",
        validation: [
            'required',
            'range(8,16)',
            'securePassword'
        ],
        asyncValidation: [],
        errorMessages: errorMessages['pass']
    },
    {
        name: "cPass",
        validation: [
            'required',
            'range(8,16)',
            'securePassword',
            'matchField(pass)'
        ],
        asyncValidation: [],
        errorMessages: errorMessages['cPass'],
    },
    {
        name: "agree",
        validation: ['required'],
        errorMessages:['You must agree with the site terms to continue'],
        asyncValidation: [],
    },
    {
        name: "captcha",
        validation: ['required','range(8,8)'],
        errorMessages:[
            'This field is required',
            'Word must be 8 chars',
        ],
        asyncValidation: [['/api/auth/validate-captcha','captcha']],
    },
    {
        name: "language",
        validation: ['required'],
        errorMessages:['You must Select your preferred language'],
        asyncValidation: [],
    },
]

let mForm : FormValidate;
let dropSelect: SelectBOX;

export function main() {

    console.log("[@@] Preparing Form class parameters");

    mForm = new FormValidate({
        form: 'trialForm',
        fields: watchFields
    });

    let selectInput =(<HTMLSelectElement> document.querySelector('#lang'));
    dropSelect = new SelectBOX(selectInput);

    let el = document.querySelector('#resetCaptcha') as HTMLElement;
    // @ts-ignore
    el.addEventListener('click', resetCaptcha, false)

    // window['flash'].flashIt({
    //     type: 'info',
    //     title: 'Globally',
    //     message: 'Using flash  message simple where it is needed',
    // });
}


function noAdminAllowed(field : ICustomElement) : IValidateResult {
    const regExp = /admin/i;
    return {
        status: !regExp.test(field.value),
        element: field,
        message: "The word ADMIN is not allowed in the user name"
    };
}
function resetCaptcha(form: FormValidate){

    console.log("Resetting Captcha");
    
    resetCaptcha()
        .then((data:any)=>{
            data = JSON.parse(data);
            (document.querySelector('#cImage') as HTMLImageElement).src = data.image;
            mForm.resetField('captcha')
        })
        .catch((error)=>{
            console.log("ERROR ",error);
        });
    function resetCaptcha(){
        let data = new FormData();
        data.append('req','new captcha');
        let reqObj = new XMLHttpRequest();
        // @ts-ignore
        return new Promise((resolve, reject) => {
            reqObj.open("POST", '/api/auth/resetCaptcha', true);
            reqObj.onload = ()=>{
                if(reqObj.status >= 200 && reqObj.status < 300){
                    resolve(reqObj.response);
                }else{
                    console.log("PROMISE ERROR " , reqObj.responseText);
                    reject(reqObj.responseText);
                }
            };
            reqObj.onerror = () => reject(reqObj.statusText);
            reqObj.setRequestHeader('X-Requested-With', 'XMLHttpRequest' );
            reqObj.send(data);
        });
    }
}